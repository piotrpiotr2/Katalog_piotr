<?php

/**
 * Albums Tags fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class AlbumsTagsFixtures.
 */
class AlbumsTagsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        if (!$this->manager || !$this->faker) {
            return;
        }

        $this->createMany(300, 'albums_tags', function () {
            $album = $this->getRandomReference('album', Album::class);
            $tag   = $this->getRandomReference('tags', Tag::class);

            $album->addTag($tag);

            return $album;
        });

        $this->manager->flush();
    }

    /**
     * Fixtures dependencies.
     *
     * @return array<int, class-string>
     *
     * @psalm-return array{0: AlbumFixtures::class, 1: TagFixtures::class}
     */
    public function getDependencies(): array
    {
        return [
            AlbumFixtures::class,
            TagFixtures::class,
        ];
    }
}
