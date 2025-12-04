<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    // single category for now
    #[ORM\ManyToOne(targetEntity: Category::class)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'createdArticles')]
    private ?User $createdBy = null;

    /** @var Collection<int, User> */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'likedArticles')]
    #[ORM\JoinTable(name: "article_likes")]
    private Collection $likedBy;

    /** @var Collection<int, User> */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'sharedArticles')]
    #[ORM\JoinTable(name: "article_shares")]
    private Collection $sharedBy;

    /** @var Collection<int, Category> */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'listOfArticles')]
    private Collection $listOfCategories;

    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
        $this->sharedBy = new ArrayCollection();
        $this->listOfCategories = new ArrayCollection();
    }

    // --- getters / setters ---

    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getContent(): ?string { return $this->content; }
    public function setContent(string $content): static { $this->content = $content; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getModifiedAt(): ?\DateTimeImmutable { return $this->modifiedAt; }
    public function setModifiedAt(\DateTimeImmutable $modifiedAt): static { $this->modifiedAt = $modifiedAt; return $this; }

    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): static { $this->category = $category; return $this; }

    public function getCreatedBy(): ?User { return $this->createdBy; }
    public function setCreatedBy(?User $user): static { $this->createdBy = $user; return $this; }

    public function getLikedBy(): Collection { return $this->likedBy; }
    public function addLikedBy(User $user): static { if (!$this->likedBy->contains($user)) { $this->likedBy->add($user); } return $this; }
    public function removeLikedBy(User $user): static { $this->likedBy->removeElement($user); return $this; }

    public function getSharedBy(): Collection { return $this->sharedBy; }
    public function addSharedBy(User $user): static { if (!$this->sharedBy->contains($user)) { $this->sharedBy->add($user); } return $this; }
    public function removeSharedBy(User $user): static { $this->sharedBy->removeElement($user); return $this; }

    public function getListOfCategories(): Collection { return $this->listOfCategories; }
    public function addListOfCategory(Category $category): static {
        if (!$this->listOfCategories->contains($category)) {
            $this->listOfCategories->add($category);
            $category->addListOfArticle($this);
        }
        return $this;
    }
    public function removeListOfCategory(Category $category): static {
        if ($this->listOfCategories->removeElement($category)) {
            $category->removeListOfArticle($this);
        }
        return $this;
    }
}
