<?php

/**
 * Favorite service.
 */

namespace App\Service;

use App\Entity\Favorite;
use App\Repository\FavoriteRepository;

/**
 * Class FavoriteService.
 */
class FavoriteService implements FavoriteServiceInterface
{
    /**
     * Favorite repository.
     */
    private readonly FavoriteRepository $favoriteRepository;

    /**
     * Constructor.
     *
     * @param FavoriteRepository $favoriteRepository Favorite repository
     */
    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    /**
     * Save entity.
     *
     * @param Favorite $favorite Favorite entity
     */
    public function save(Favorite $favorite): void
    {
        $this->favoriteRepository->save($favorite);
    }

    /**
     * Delete entity.
     *
     * @param Favorite $favorite Favorite entity
     */
    public function delete(Favorite $favorite): void
    {
        $this->favoriteRepository->delete($favorite);
    }

    /**
     * Find by id.
     *
     * @param array $id Favorite id
     *
     * @return Favorite[] Array of Favorite entities
     */
    public function findBy(array $id): array
    {
        return $this->favoriteRepository->findBy(['album' => $id]);
    }
}
