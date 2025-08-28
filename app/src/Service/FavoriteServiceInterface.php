<?php

/**
 * Favorite service interface.
 */

namespace App\Service;

use App\Entity\Favorite;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Interface FavoriteServiceInterface.
 */
interface FavoriteServiceInterface
{
    /**
     * Save entity.
     *
     * @param Favorite $favorite Favorite entity
     */
    public function save(Favorite $favorite): void;

    /**
     * Delete entity.
     *
     * @param Favorite $favorite Favorite entity
     */
    public function delete(Favorite $favorite): void;

    /**
     * Find by id.
     *
     * @param array $id Favorite id
     *
     * @return Favorite[] Array of Favorite entities
     *
     * @throws NonUniqueResultException
     */
    public function findBy(array $id): array;
}
