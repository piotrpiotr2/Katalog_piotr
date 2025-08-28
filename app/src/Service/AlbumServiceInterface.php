<?php

/**
 * Album service interface.
 */

namespace App\Service;

use App\Dto\AlbumListInputFiltersDto;
use App\Entity\Album;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AlbumServiceInterface.
 */
interface AlbumServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, AlbumListInputFiltersDto $filters): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Album $album Album entity
     */
    public function save(Album $album): void;

    /**
     * Delete entity.
     *
     * @param Album $album Album entity
     */
    public function delete(Album $album): void;
}
