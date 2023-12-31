<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
//#[ORM\Table(name: 'local_bookable.users')]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
//TODO: check if user needs to implement both interfaces
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $first_name = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $last_name = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Avatar $avatar = null;
    #[ORM\Column(length: 512, nullable: true)]
    private ?string $bio = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FollowedBook::class)]
    private Collection $followedBooks;

    #[ORM\Column]
    private array $roles = [];

    public function __construct()
    {
        $this->followedBooks = new ArrayCollection();
        $this->likedGenres = new ArrayCollection();
        $this->likedBooks = new ArrayCollection();
        $this->dislikedBooks = new ArrayCollection();
    }

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: LikedGenre::class)]
    private Collection $likedGenres;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: LikedBook::class)]
    private Collection $likedBooks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DislikedBook::class)]
    private Collection $dislikedBooks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /*
     * Custom user fields
     */

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(?Avatar $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

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
            $followedBook->setUser($this);
        }

        return $this;
    }

    public function removeFollowedBook(FollowedBook $followedBook): self
    {
        if ($this->followedBooks->removeElement($followedBook)) {
            // set the owning side to null (unless already changed)
            if ($followedBook->getUser() === $this) {
                $followedBook->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
            $likedGenre->setUser($this);
        }

        return $this;
    }

    public function removeLikedGenre(LikedGenre $likedGenre): self
    {
        if ($this->likedGenres->removeElement($likedGenre)) {
            // set the owning side to null (unless already changed)
            if ($likedGenre->getUser() === $this) {
                $likedGenre->setUser(null);
            }
        }

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
            $likedBook->setUser($this);
        }

        return $this;
    }

    public function removeLikedBook(LikedBook $likedBook): self
    {
        if ($this->likedBooks->removeElement($likedBook)) {
            // set the owning side to null (unless already changed)
            if ($likedBook->getUser() === $this) {
                $likedBook->setUser(null);
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
            $dislikedBook->setUser($this);
        }

        return $this;
    }

    public function removeDislikedBook(DislikedBook $dislikedBook): self
    {
        if ($this->dislikedBooks->removeElement($dislikedBook)) {
            // set the owning side to null (unless already changed)
            if ($dislikedBook->getUser() === $this) {
                $dislikedBook->setUser(null);
            }
        }

        return $this;
    }
}
