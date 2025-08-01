<?php

/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class CategoryFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Music genres.
     */
    private array $musicGenres = [
        'Rock',
        'Pop',
        'Jazz',
        'Classical',
        'Hip Hop',
        'Electronic',
        'Reggae',
        'Country',
        'Blues',
        'Metal',
        'Folk',
        'R&B',
        'Soul',
    ];

    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        $this->createMany(13, 'category', function (int $i) {
            $category = new Category();

            $genre = $this->faker->unique()->randomElement($this->musicGenres);
            $category->setTitle($genre);
            $category->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $category->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            return $category;
        });
    }
}
