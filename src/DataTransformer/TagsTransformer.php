<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

final class TagsTransformer implements DataTransformerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @param Collection<int, Tag> $value
     * @return string
     */
    public function transform($value): string
    {
        return implode(',', $value->map(static fn (Tag $tag): string => $tag->getName())->toArray());
    }

    /**
     * @param string $value
     * @return Collection<int, Taf>
     */
    public function reverseTransform($value): Collection
    {
        $tags = u($value)->split(',');

        $tagsCollection = new ArrayCollection();
        $tagsRepository = $this->entityManager->getRepository(Tag::class);
        array_walk($tags, static fn(string &$tagName) => u($tagName)->trim());
dd($tags);
        foreach ($tags as $tagName){
            $tag = $tagsRepository->findOneBy(['name' => $tagName]);
        }

        return $tagsCollection;
    }
}