<?php

namespace App\Entity;

use App\Entity\Category;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArticlesRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert; 

#[ApiResource(
    paginationItemsPerPage: 10,
    normalizationContext: ['groups' => ['read:article']],
    denormalizationContext: ['groups' => ['write:article']],
)]
#[Post(security: 'is_granted("ROLE_ADMIN")')]
#[Patch(security: 'is_granted("ROLE_ADMIN")')]
#[GetCollection]
#[Delete(security: 'is_granted("ROLE_ADMIN")')]
#[ORM\Entity(repositoryClass: ArticlesRepository::class,)]
#[ORM\Table(name: 'article')]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:article'])]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide')]
    #[Assert\Length(min: 3, max: 100)]
    #[Groups(['read:article', 'write:article'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(['read:article', 'write:article'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Groups(['read:article'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[Groups(['read:article', 'write:article'])]
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    private Collection $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->createdAt = new \DateTime("Europe/Paris");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
