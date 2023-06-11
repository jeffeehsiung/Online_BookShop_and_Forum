<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
//#[ORM\Table(name: 'local_bookable.books')]
#[ORM\Table(name: 'books')]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $original_publication_year = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $likes = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $image_url = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $small_image_url = null;

    #[ORM\Column(nullable: true)]
    private ?int $genre_id = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Author $author = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Genre $genre = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $dislikes = null;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: LikedBook::class)]
    private Collection $likedBooks;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: DislikedBook::class)]
    private Collection $dislikedBooks;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: FollowedBook::class)]
    private Collection $followedBooks;

    public function __construct()
    {
        $this->followedBooks = new ArrayCollection();
        $this->likedBooks = new ArrayCollection();
        $this->dislikedBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getGenreId(): ?int
    {
        return $this->genre_id;
    }

    public function getOriginalPublicationYear(): ?string
    {
        return $this->original_publication_year;
    }

    public function setOriginalPublicationYear(?string $original_publication_year): self
    {
        $this->original_publication_year = $original_publication_year;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(?string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getSmallImageUrl(): ?string
    {
        return $this->small_image_url;
    }

    public function setSmallImageUrl(?string $small_image_url): self
    {
        $this->small_image_url = $small_image_url;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
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

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    public function setDislikes(?int $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    /**
     * @return Collection<int, LikedBook>
     */
    public function getLikedBooks(): Collection
    {
        return $this->likedBooks;
    }

    public function addLikedBook(LikedBook $likedBook): self
    {
        if (!$this->likedBooks->contains($likedBook)) {
            $this->likedBooks->add($likedBook);
            $likedBook->setBook($this);
        }

        return $this;
    }

    public function removeLikedBook(LikedBook $likedBook): self
    {
        if ($this->likedBooks->removeElement($likedBook)) {
            // set the owning side to null (unless already changed)
            if ($likedBook->getBook() === $this) {
                $likedBook->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DislikedBook>
     */
    public function getDislikedBooks(): Collection
    {
        return $this->dislikedBooks;
    }

    public function addDislikedBook(DislikedBook $dislikedBook): self
    {
        if (!$this->dislikedBooks->contains($dislikedBook)) {
            $this->dislikedBooks->add($dislikedBook);
            $dislikedBook->setBook($this);
        }

        return $this;
    }

    public function removeDislikedBook(DislikedBook $dislikedBook): self
    {
        if ($this->dislikedBooks->removeElement($dislikedBook)) {
            // set the owning side to null (unless already changed)
            if ($dislikedBook->getBook() === $this) {
                $dislikedBook->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FollowedBook>
     */
    public function getFollowedBooks(): Collection
    {
        return $this->followedBooks;
    }

    public function addFollowedBook(FollowedBook $followedBook): self
    {
        if (!$this->followedBooks->contains($followedBook)) {
            $this->followedBooks->add($followedBook);
            $followedBook->setBook($this);
        }

        return $this;
    }

    public function removeFollowedBook(FollowedBook $followedBook): self
    {
        if ($this->followedBooks->removeElement($followedBook)) {
            // set the owning side to null (unless already changed)
            if ($followedBook->getBook() === $this) {
                $followedBook->setBook(null);
            }
        }

        return $this;
    }
}
