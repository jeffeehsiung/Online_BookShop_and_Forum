<?php

namespace App\Entity;

use App\Repository\FollowedBookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowedBookRepository::class)]
#[ORM\Table(name: 'local_bookable.followed_books')]
class FollowedBook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'followedBooks')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'followedBooks')]
    private ?Book $book = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
