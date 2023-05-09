<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'a22web12.books')]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?int $goodreads_book_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $best_book_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $work_id = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $books_count = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $original_publication_year = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(length: 17, nullable: true)]
    private ?string $isbn13 = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $language_code = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $likes = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $image_url = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $small_image_url = null;

    #[ORM\ManyToOne(inversedBy: 'help')]
    private ?Author $author = null;

    #[ORM\ManyToOne(inversedBy: 'help')]
    private ?Genre $genre = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $dislikes = null;

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

    public function getGoodreadsBookId(): ?int
    {
        return $this->goodreads_book_id;
    }

    public function setGoodreadsBookId(?int $goodreads_book_id): self
    {
        $this->goodreads_book_id = $goodreads_book_id;

        return $this;
    }

    public function getBestBookId(): ?int
    {
        return $this->best_book_id;
    }

    public function setBestBookId(?int $best_book_id): self
    {
        $this->best_book_id = $best_book_id;

        return $this;
    }

    public function getWorkId(): ?int
    {
        return $this->work_id;
    }

    public function setWorkId(?int $work_id): self
    {
        $this->work_id = $work_id;

        return $this;
    }

    public function getBooksCount(): ?string
    {
        return $this->books_count;
    }

    public function setBooksCount(?string $books_count): self
    {
        $this->books_count = $books_count;

        return $this;
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

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getIsbn13(): ?string
    {
        return $this->isbn13;
    }

    public function setIsbn13(?string $isbn13): self
    {
        $this->isbn13 = $isbn13;

        return $this;
    }

    public function getLanguageCode(): ?string
    {
        return $this->language_code;
    }

    public function setLanguageCode(?string $language_code): self
    {
        $this->language_code = $language_code;

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

    public function getDislikes(): ?string
    {
        return $this->dislikes;
    }

    public function setDislikes(?string $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }
}
