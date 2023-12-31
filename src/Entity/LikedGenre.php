<?php

namespace App\Entity;

use App\Repository\LikedGenreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikedGenreRepository::class)]
//#[ORM\Table(name: 'local_bookable.liked_genre')]
#[ORM\Table(name: 'liked_genre')]
class LikedGenre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likedGenres')]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(inversedBy: 'likedGenres')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
