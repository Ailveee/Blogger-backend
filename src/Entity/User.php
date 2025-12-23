<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    private Collection $createdArticles;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'likedBy')]
    private Collection $likedArticles;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'sharedBy')]
    private Collection $sharedArticles;

    public function __construct()
    {
        $this->createdArticles = new ArrayCollection();
        $this->likedArticles = new ArrayCollection();
        $this->sharedArticles = new ArrayCollection();
    }

    /* =========================
       REQUIRED BY SYMFONY
       ========================= */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void {}

    /* =========================
       EMAIL
       ========================= */

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /* =========================
       ROLES
       ========================= */

    public function getRoles(): array
    {
        return array_unique(array_merge($this->roles, ['ROLE_USER']));
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /* =========================
       PASSWORD
       ========================= */

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /* =========================
       NAME  ✅ THIS FIXES YOUR ERROR
       ========================= */

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self   // ← THIS WAS MISSING
    {
        $this->name = $name;
        return $this;
    }

    /* =========================
       RELATIONS
       ========================= */

    public function getCreatedArticles(): Collection
    {
        return $this->createdArticles;
    }

    public function getLikedArticles(): Collection
    {
        return $this->likedArticles;
    }

    public function getSharedArticles(): Collection
    {
        return $this->sharedArticles;
    }
}
