<?php

/**
 * Tags Data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Service\TagServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 *
 * @implements DataTransformerInterface<mixed, mixed>
 */
class TagsDataTransformer implements DataTransformerInterface
{

    /**
     * Constructor.
     *
     * @param TagServiceInterface $tagService Tag service interface
     */
    public function __construct(private readonly TagServiceInterface $tagService)
    {
    }

    /**
     * Transform a collection of tags into a comma-separated string.
     *
     * @param Collection<int, Tag>|null $value Tags entity collection
     *
     * @return string Tags
     */
    public function transform($value): string
    {
        if (null === $value || $value->isEmpty()) {
            return '';
        }

        $tagTitles = [];
        foreach ($value as $tag) {
            $tagTitles[] = $tag->getTitle(); // make sure your Tag entity has getTitle()
        }

        return implode(', ', $tagTitles);
    }

    /**
     * Transform a comma-separated string into a Collection of Tag entities.
     *
     * @param string|null $value
     *
     * @return Collection<int, Tag>
     */
    public function reverseTransform($value): Collection
    {
        $tags = new ArrayCollection();

        if (!$value) {
            return $tags;
        }

        $tagTitles = explode(',', $value);

        foreach ($tagTitles as $tagTitle) {
            $tagTitle = trim($tagTitle);
            if ('' === $tagTitle) {
                continue;
            }

            $tag = $this->tagService->findOneByTitle(strtolower($tagTitle));
            if (null === $tag) {
                $tag = new Tag();
                $tag->setTitle($tagTitle); // or setName() if that's your actual field
                $this->tagService->save($tag);
            }

            if (!$tags->contains($tag)) {
                $tags->add($tag);
            }
        }

        return $tags;
    }
}
