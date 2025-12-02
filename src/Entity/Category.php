<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /** @var Collection<int, Article> */
    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'listOfCategories')]
    #[ORM\JoinTable(name: "article_category")]
    private Collection $listOfArticles;

    public function __construct()
    {
        $this->listOfArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getListOfArticles(): Collection
    {
        return $this->listOfArticles;
    }

    public function addListOfArticle(Article $article): static
    {
        if (!$this->listOfArticles->contains($article)) {
            $this->listOfArticles->add($article);
        }
        return $this;
    }

    public function removeListOfArticle(Article $article): static
    {
        $this->listOfArticles->removeElement($article);
        return $this;
    }
}
