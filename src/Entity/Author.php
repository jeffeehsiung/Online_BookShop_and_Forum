<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: 'a22web12.authors')]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $author_name = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
    private Collection $books;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: LikedAuthor::class)]
    private Collection $likedAuthors;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->likedAuthors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorName(): ?string
    {
        return $this->author_name;
    }

    public function setAuthorName(?string $authorName): self
    {
        $this->author_name = $authorName;

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
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LikedAuthor>
     */
    public function getLikedAuthors(): Collection
    {
        return $this->likedAuthors;
    }

    public function addLikedAuthor(LikedAuthor $likedAuthor): self
    {
        if (!$this->likedAuthors->contains($likedAuthor)) {
            $this->likedAuthors->add($likedAuthor);
            $likedAuthor->setAuthor($this);
        }

        return $this;
    }

    public function removeLikedAuthor(LikedAuthor $likedAuthor): self
    {
        if ($this->likedAuthors->removeElement($likedAuthor)) {
            // set the owning side to null (unless already changed)
            if ($likedAuthor->getAuthor() === $this) {
                $likedAuthor->setAuthor(null);
            }
        }

        return $this;
    }
}
