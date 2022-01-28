<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: UserCollection::class, inversedBy: 'item')]
    #[ORM\JoinColumn(nullable: false)]
    private $userCollection;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: Value::class, cascade: ["persist"], orphanRemoval: true)]
    private $item_values;

    private $attributes;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'item', cascade: ['persist'])]
    private $tags;

    public function __construct()
    {
        $this->item_values = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    /**
     * @return ArrayCollection
     */
    public function getAttributes(): ArrayCollection
    {
        return $this->attributes;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
//            $tag->addItem($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeItem($this);
        }

        return $this;
    }
}
