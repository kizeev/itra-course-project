<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{
    public function __construct(
        private ManagerRegistry $doctrine,
    )
    {}

    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($value): ArrayCollection
    {
        $tagCollection = new ArrayCollection();

        foreach ($value as $tag) {
            $tagInRepo = $this->doctrine->getRepository(Tag::class)
                ->findOneBy(['name' => $tag->getName()]);
            if ($tagInRepo !== null) {
                $tagCollection->add($tagInRepo);
            }
            else {
                $tagCollection->add($tag);
            }
        }

        return $tagCollection;
    }
}