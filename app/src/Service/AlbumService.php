<?php

/**
 * Album service.
 */

namespace App\Service;

use App\Dto\AlbumListFiltersDto;
use App\Dto\AlbumListInputFiltersDto;
use App\Entity\Album;
use App\Repository\AlbumRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AlbumService.
 */
class AlbumService implements AlbumServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService Category service
     * @param PaginatorInterface       $paginator       Paginator
     * @param TagServiceInterface      $tagService      Tag service
     * @param AlbumRepository          $albumRepository Album repository
     */
    public function __construct(private readonly CategoryServiceInterface $categoryService, private readonly PaginatorInterface $paginator, private readonly TagServiceInterface $tagService, private readonly AlbumRepository $albumRepository)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int                      $page    Page number
     * @param AlbumListInputFiltersDto $filters Filters
     *
     * @return PaginationInterface< SlidingPagination> Paginated list
     */
    public function getPaginatedList(int $page, AlbumListInputFiltersDto $filters): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->albumRepository->queryAll($filters),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['album.id', 'album.createdAt', 'album.updatedAt', 'album.title', 'category.title'],
                'defaultSortFieldName' => 'album.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Save entity.
     *
     * @param Album $album Album entity
     */
    public function save(Album $album): void
    {
        $this->albumRepository->save($album);
    }

    /**
     * Delete entity.
     *
     * @param Album $album Album entity
     */
    public function delete(Album $album): void
    {
        $this->albumRepository->delete($album);
    }

    /**
     * Prepare filters for the albums list.
     *
     * @param AlbumListInputFiltersDto $filters Raw filters from request
     *
     * @return AlbumListFiltersDto Result filters
     */
    private function prepareFilters(AlbumListInputFiltersDto $filters): AlbumListFiltersDto
    {
        return new AlbumListFiltersDto(
            null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null,
            null !== $filters->tagId ? $this->tagService->findOneById($filters->tagId) : null
        );
    }
}
