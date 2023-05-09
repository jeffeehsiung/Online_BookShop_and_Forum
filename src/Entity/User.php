<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'a22web12.users')]
class User
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Avatar $avatar = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $location = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Library $library = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone_number = null;

    #[ORM\Column(nullable: true)]
    private ?int $share_location = null;

    #[ORM\Column(nullable: true)]
    private ?int $share_phone_number = null;

    #[ORM\Column(nullable: true)]
    private ?int $visibility_profile = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $bio = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FollowedBook::class)]
    private Collection $followedBooks;

    public function __construct()
    {
        $this->Book = new ArrayCollection();
        $this->followedBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getLibrary(): ?Library
    {
        return $this->library;
    }

    public function setLibrary(?Library $library): self
    {
        $this->library = $library;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getShareLocation(): ?int
    {
        return $this->share_location;
    }

    public function setShareLocation(?int $share_location): self
    {
        $this->share_location = $share_location;

        return $this;
    }

    public function getSharePhoneNumber(): ?int
    {
        return $this->share_phone_number;
    }

    public function setSharePhoneNumber(?int $share_phone_number): self
    {
        $this->share_phone_number = $share_phone_number;

        return $this;
    }

    public function getVisibilityProfile(): ?int
    {
        return $this->visibility_profile;
    }

    public function setVisibilityProfile(?int $visibility_profile): self
    {
        $this->visibility_profile = $visibility_profile;

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
}
