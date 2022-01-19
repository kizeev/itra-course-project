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
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: UserCollection::class, orphanRemoval: true)]
    private $categoryCollections;

    public function __construct()
    {
        $this->categoryCollections = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|UserCollection[]
     */
    public function getCategoryCollections(): Collection
    {
        return $this->categoryCollections;
    }

    public function addUserCollection(UserCollection $userCollection): self
    {
        if (!$this->categoryCollections->contains($userCollection)) {
            $this->categoryCollections[] = $userCollection;
            $userCollection->setCategory($this);
        }

        return $this;
    }

    public function removeUserCollection(UserCollection $userCollection): self
    {
        if ($this->categoryCollections->removeElement($userCollection)) {
            // set the owning side to null (unless already changed)
            if ($userCollection->getCategory() === $this) {
                $userCollection->setCategory(null);
            }
        }

        return $this;
    }
}
