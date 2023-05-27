<?php

namespace App\Controller;

use App\Repository\AvatarRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        // TODO: split twig templates into file format 'controllername/methodname.html.twig' -> example: 'bookable/settings.html.twig'
        $stylesheets = ['settings.css'];
        $javascripts = ['settings.js'];

        return $this->render('setting.html.twig',[
            'user' => $user,
            'avatars' => $avatars,
            'stylesheets' => $stylesheets,
            'javascripts' => $javascripts,
            'bookgenres' => $bookGenres
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
        $avatarID = $request->request->get('avatar-id', 0);

        // push to database if avatar was found
        if($avatarID) {
            $avatar = $avatarRepository->findOneBy(['id' => $avatarID]);
            $user->setAvatar($avatar);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute("settings");
    }
}