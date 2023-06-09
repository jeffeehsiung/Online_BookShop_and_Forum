<?php

namespace App\Controller;

use App\Entity\LikedGenre;
use App\Repository\AvatarRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: "settings")]
    public function settings
    (
        GenreRepository $genreRepository, AvatarRepository $avatarRepository
    ): Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $bookGenres = $genreRepository->findAll();

        $avatars = $avatarRepository->findAll();

        // build genres array from likedgenres of user
        $likedGenres = $user->getLikedGenres();
        $favouriteGenresInitial = array();
        foreach ($likedGenres as $genre) {
            array_push($favouriteGenresInitial, $genre->getGenre());
        }

        // TODO: split twig templates into file format 'controllername/methodname.html.twig' -> example: 'bookable/settings.html.twig'
        $stylesheets = ['settings.css'];
        $javascripts = ['settings.js'];

        return $this->render('setting.html.twig',[
            'user' => $user,
            'avatars' => $avatars,
            'stylesheets' => $stylesheets,
            'javascripts' => $javascripts,
            'bookgenres' => $bookGenres,
            'favouriteGenresInitial' => $favouriteGenresInitial
        ]);
    }
    #[Route('/settings/setAvatar', name: "setAvatar", methods: ['POST'])]
    public function setAvatar
    (
        Request $request, AvatarRepository $avatarRepository, EntityManagerInterface $entityManager
    ) : Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // get avatar from form
        $avatar_id = $request->request->get('avatar_id', 0);

        // push to database if avatar was found
        if($avatar_id) {
            $avatar = $avatarRepository->findOneBy(['id' => $avatar_id]);
            $user->setAvatar($avatar);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute("settings");
    }

    #[Route('/settings/setBio', name:"setBio", methods: ['POST'])]
    public function setBio
    (
        Request $request, EntityManagerInterface $entityManager
    ) : Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // get bio from form
        $bio = $request->request->get('bio');

        // push to db is bio retrieved
        if($bio) {
            $user->setBio($bio);
            $entityManager->flush();
        }

        return $this->redirectToRoute("settings");
    }

    #[Route('/settings/editLikedGenres', name:"editLikedGenres", methods: ['POST'])]
    public function editLikedGenres
    (
        Request $request, EntityManagerInterface $entityManager, GenreRepository $genreRepository
    ) : Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $newLikedGenreIDs = array_values($request->request->all());

        // remove liked genres
        $likedGenres = $user->getLikedGenres();
        foreach ($likedGenres as $likedGenre) {
            $user->removeLikedGenre($likedGenre);
            $entityManager->remove($likedGenre);
        }

        // add new liked genres
        foreach ($newLikedGenreIDs as $id) {
            $likedGenre = new LikedGenre();
            $likedGenre
                ->setUser($user)
                ->setGenre($genreRepository->findOneBy(['id' => $id]));
            $entityManager->persist($likedGenre);
            $user->addLikedGenre($likedGenre);
        }

        $entityManager->flush();
        return $this->redirectToRoute("settings");
    }

    #[Route('/settings/editPassword', name:"editPassword", methods: ['POST'])]
    public function editPassword
    (
        Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher
    ) : Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // update password if valid, pass error message if not and redirect to settings page
        $currentUnHashedPassword = $request->request->get('current_password');
        if($passwordHasher->isPasswordValid($user, $currentUnHashedPassword)) {
            $newHashedPassword = $passwordHasher->hashPassword(
                $user,
                $request->request->get('new_password')
            );
            $user->setPassword($newHashedPassword);
            $this->addFlash('password_success', 'password was edited succesfully');
        } else {
            $this->addFlash(
                'password_fail',
                "changing password failed because 'current password' field was filled in wrong, retry"
            );
        }

        $entityManager->flush();
        return $this->redirectToRoute("settings");
    }
}