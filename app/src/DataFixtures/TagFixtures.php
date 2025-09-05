<?php

/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;

/**
 * Class TagFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(30, 'tags', function () {
            $tag = new Tag();

            $title = $this->generateValidTitle();
            $tag->setTitle($title);

            return $tag;
        });

        $this->manager->flush();
    }

    /**
     * Generate tag title.
     *
     * @return string Title
     */
    private function generateValidTitle(): string
    {
        do {
            $title = $this->faker->unique()->word;
        } while (strlen($title) < 3);

        return $title;
    }
}
