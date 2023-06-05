<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use App\Repository\AvatarRepository;
use App\Repository\GenreRepository;
use App\Repository\LikedGenreRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SettingsControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;

    private User $testUser;

    /**
     * @group include
     * @throws \Exception
     */
    protected function setUp(): void
    {
        // Create a test client
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->testUser = $userRepository->findOneBy(['email' => 'test@test.com']);
        $this->client->loginUser($this->testUser);

        // Get the necessary services and repositories
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @group include
     * @throws \Exception
     */
    public function testSettings(): void
    {
        // Make a request to the settings endpoint
        $this->client->request('GET', '/settings');

        // Assert the response status code
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('title', 'Settings');
    }

    /**
     * @group include
     * @throws \Exception
     */
    public function testSetAvatar(): void
    {
        // Get an avatar for testing
        $avatarRepository = static::getContainer()->get(AvatarRepository::class);
        $avatar = $avatarRepository->findOneBy(['id' => 1]);

        $initialAvatar = $this->testUser->getAvatar();

        // Make a request to the setAvatar endpoint
        $this->client->request(
            'POST',
            '/settings/setAvatar',
            ['avatar-id' => $avatar->getId()]
        );

        // Assert the response status code
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        // Assert that the user's avatar has been updated
        $this->assertSame($avatar, $this->testUser->getAvatar());

        // Clean up the database
        $this->testUser->setAvatar($initialAvatar);
        $this->entityManager->flush();
    }

    /**
     * @group include
     * @throws \Exception
     */
    public function testSetBio(): void
    {
        $initialBio = $this->testUser->getBio();

        // Make a request to the setBio endpoint
        $this->client->request(
            'POST',
            '/settings/setBio',
            ['bio' => 'Test bio']
        );

        // Assert the response status code
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        // Assert that the user's bio has been updated
        $this->assertSame('Test bio', $this->testUser->getBio());

        // Clean up the database
        $this->testUser->setBio($initialBio);
        $this->entityManager->flush();
    }

    /**
     * @group exclude
     * @throws \Exception
     */
    public function testEditLikedGenres(): void
    {
        $genreRepository = static::getContainer()->get(GenreRepository::class);
        $genre1 = $genreRepository->findOneBy(['id' => 1]);
        $genre2 = $genreRepository->findOneBy(['id' => 2]);
        // Make a request to the editLikedGenres endpoint
        $this->client->request(
            'POST',
            '/settings/editLikedGenres',
            [
                $genre1->getGenre() => $genre1->getId(),
                $genre2->getGenre() => $genre2->getId()
            ] // IDs of the genres
        );

        // Assert the response status code
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        // Assert that the user's liked genres have been updated
        $likedGenres = $this->testUser->getLikedGenres();
        $this->assertCount(2, $likedGenres);
        $likedGenreRepository = static::getContainer()->get(LikedGenreRepository::class);
        $likedGenre1 = $likedGenreRepository->findOneBy([
            'user' => $this->testUser,
            'genre' => $genreRepository->findOneBy(['id' => 1])
        ]);
        $likedGenre2 = $likedGenreRepository->findOneBy([
            'user' => $this->testUser,
            'genre' => $genreRepository->findOneBy(['id' => 2])
        ]);
        $this->assertContains($likedGenre1, $likedGenres);
        $this->assertContains($likedGenre2, $likedGenres);

        // Clean up the database
        $this->entityManager->remove($likedGenre1);
        $this->entityManager->remove($likedGenre2);
        $this->entityManager->flush();
    }

    /**
     * @group include
     * @throws \Exception
     */
    public function testEditPassword(): void
    {
        $passwordHasher = $this->client->getContainer()->get('security.password_hasher');
        $oldHashedPassword = $this->testUser->getPassword();

        // Make a request to the editPassword endpoint with valid data
        $newPassword = 'newpassword';
        $this->client->request(
            'POST',
            '/settings/editPassword',
            [
                'current-password' => 'password',
                'new-password' => $newPassword
            ]
        );

        // Assert the response status code
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        // Assert that the user's password has been updated
        $this->assertTrue($passwordHasher->isPasswordValid($this->testUser, $newPassword));

        // Clean up database
        $this->testUser->setPassword($oldHashedPassword);
        $this->entityManager->flush();
    }
}
