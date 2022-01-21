<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $tag;

    #[ORM\ManyToOne(targetEntity: UserCollection::class, inversedBy: 'item')]
    #[ORM\JoinColumn(nullable: false)]
    private $userCollection;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: Value::class, orphanRemoval: true)]
    private $item_values;

    public function __construct()
    {
        $this->item_values = new ArrayCollection();
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

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getUserCollection(): ?UserCollection
    {
        return $this->userCollection;
    }

    public function setUserCollection(?UserCollection $userCollection): self
    {
        $this->userCollection = $userCollection;

        return $this;
    }

    /**
     * @return Collection|Value[]
     */
    public function getItemValues(): Collection
    {
        return $this->item_values;
    }

    public function addItemValue(Value $itemValue): self
    {
        if (!$this->item_values->contains($itemValue)) {
            $this->item_values[] = $itemValue;
            $itemValue->setItem($this);
        }

        return $this;
    }

    public function removeItemValue(Value $itemValue): self
    {
        if ($this->item_values->removeElement($itemValue)) {
            // set the owning side to null (unless already changed)
            if ($itemValue->getItem() === $this) {
                $itemValue->setItem(null);
            }
        }

        return $this;
    }
}
