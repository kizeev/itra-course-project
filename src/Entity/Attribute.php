<?php

namespace App\Entity;

use App\Repository\AttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributeRepository::class)]
class Attribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $field_name;

    #[ORM\ManyToMany(targetEntity: UserCollection::class, mappedBy: 'attribute')]
    private $userCollections;

    #[ORM\ManyToOne(targetEntity: AttributeType::class, inversedBy: 'attributes')]
    #[ORM\JoinColumn(nullable: false)]
    private $type;

    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: Value::class, cascade: ['persist', 'remove'])]
    private $value;

    public function __construct()
    {
        $this->userCollections = new ArrayCollection();
        $this->type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldname(): ?string
    {
        return $this->field_name;
    }

    public function setFieldname(string $field_name): self
    {
        $this->field_name = $field_name;

        return $this;
    }

    /**
     * @return Collection|UserCollection[]
     */
    public function getUserCollections(): Collection
    {
        return $this->userCollections;
    }

    public function addUserCollection(UserCollection $userCollection): self
    {
        if (!$this->userCollections->contains($userCollection)) {
            $this->userCollections[] = $userCollection;
            $userCollection->addAttribute($this);
        }

        return $this;
    }

    public function removeUserCollection(UserCollection $userCollection): self
    {
        if ($this->userCollections->removeElement($userCollection)) {
            $userCollection->removeAttribute($this);
        }

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(?AttributeType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?Value
    {
        return $this->value;
    }

    public function setValue(Value $value): self
    {
        // set the owning side of the relation if necessary
        if ($value->getAttribute() !== $this) {
            $value->setAttribute($this);
        }

        $this->value = $value;

        return $this;
    }
}
