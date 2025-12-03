<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /** @var Collection<int, Article> */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'createdBy')]
    private Collection $createdArticles;

    /** @var Collection<int, Article> */
    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'likedBy')]
    private Collection $likedArticles;

    /** @var Collection<int, Article> */
    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'sharedBy')]
    private Collection $sharedArticles;

    public function __construct()
    {
        $this->createdArticles = new ArrayCollection();
        $this->likedArticles = new ArrayCollection();
        $this->sharedArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // --- Name / Email / Password setters & getters ---

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     * Used by Symfony security (>=5.3) instead of getUsername().
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // For backwards compatibility (some code may still call getUsername)
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    // --- Roles ---

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    // --- PasswordAuthenticatedUserInterface ---

    public function getPassword(): string
    {
        // PasswordAuthenticatedUserInterface requires a non-null string
        return (string) $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    // If you store temporary sensitive data, clear it here
    public function eraseCredentials(): void
    {
        // $this->plainPassword = null;
    }

    // --- Collections ---

    // Created articles
    public function getCreatedArticles(): Collection
    {
        return $this->createdArticles;
    }

    // Liked articles
    public function getLikedArticles(): Collection
    {
        return $this->likedArticles;
    }

    // Shared articles
    public function getSharedArticles(): Collection
    {
        return $this->sharedArticles;
    }
}
