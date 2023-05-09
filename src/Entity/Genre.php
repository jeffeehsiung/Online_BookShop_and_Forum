<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\Table(name: 'a22web12.genres')]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $genre = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: Book::class)]
    private Collection $books;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: LikedGenre::class)]
    private Collection $likedGenres;



    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->likedGenres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setGenre($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getGenre() === $this) {
                $book->setGenre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LikedGenre>
     */
    public function getLikedGenres(): Collection
    {
        return $this->likedGenres;
    }

    public function addLikedGenre(LikedGenre $likedGenre): self
    {
        if (!$this->likedGenres->contains($likedGenre)) {
            $this->likedGenres->add($likedGenre);
            $likedGenre->setGenre($this);
        }

        return $this;
    }

    public function removeLikedGenre(LikedGenre $likedGenre): self
    {
        if ($this->likedGenres->removeElement($likedGenre)) {
            // set the owning side to null (unless already changed)
            if ($likedGenre->getGenre() === $this) {
                $likedGenre->setGenre(null);
            }
        }

        return $this;
    }
}
