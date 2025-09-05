<?php

/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class CommentFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
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

        $this->createMany(200, 'comments', function (int $i) {
            $comment = new Comment();

            $contentLen = $this->faker->numberBetween(40, 300);
            $comment->setContent($this->faker->realText($contentLen));

            $comment->setCreatedAt(\DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-100 days', 'now')
            ));

            /** @var Album $album */
            $album = $this->getRandomReference('album', Album::class);
            $comment->setAlbum($album);


            if ($this->faker->boolean(70)) {
                /** @var User $user */
                $user = $this->getRandomReference('user', User::class);
                $comment->setUser($user);
            } else {
                $comment->setUser(null);
                $comment->setGuestNickname($this->faker->firstName());
                $comment->setGuestEmail($this->faker->unique()->safeEmail());
            }

            return $comment;
        });

        $this->manager->flush();
    }

    /**
     * Fixtures dependencies.
     *
     * @return array<int, class-string>
     *
     * @psalm-return array{0: AlbumFixtures::class, 1: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        return [
            AlbumFixtures::class,
            UserFixtures::class,
        ];
    }
}
