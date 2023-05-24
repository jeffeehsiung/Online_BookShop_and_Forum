<?php

namespace App\Entity;

use App\Repository\ReadBooksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReadBooksRepository::class)]
#[ORM\Table(name: 'local_bookable.read_books')]
class ReadBooks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;
    #[ORM\ManyToOne(inversedBy: 'readBooks')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'readBooks')]
    private ?Book $book = null;

    #[ORM\Column]
    private ?int $book_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBookId(): ?int
    {
        return $this->book_id;
    }

    public function setBookId(int $book_id): self
    {
        $this->book_id = $book_id;

        return $this;
    }
}
