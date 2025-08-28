<?php

/**
 * Album repository.
 */

namespace App\Repository;

use App\Dto\AlbumListFiltersDto;
use App\Entity\Category;
use App\Entity\Album;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AlbumRepository.
 *
 * @extends ServiceEntityRepository<Album>
 */
class AlbumRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    /**
     * Query all records.
     *
     * @param AlbumListFiltersDto $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(AlbumListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('album')
            ->select(
                'partial album.{id, createdAt, updatedAt, title}',
                'partial category.{id, title}',
                'partial tags.{id, title}'
            )
            ->join('album.category', 'category')
            ->leftJoin('album.tags', 'tags');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }


    /**
     * Count albums by category.
     *
     * @param Category $category Category
     *
     * @return int Number of albums in category
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->createQueryBuilder('album');

        return $qb->select($qb->expr()->countDistinct('album.id'))
            ->where('album.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * Save entity.
     *
     * @param Album $album Album entity
     */
    public function save(Album $album): void
    {
        $this->getEntityManager()->persist($album);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Album $album Album entity
     */
    public function delete(Album $album): void
    {
        $this->getEntityManager()->remove($album);
        $this->getEntityManager()->flush();
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder        $queryBuilder Query builder
     * @param AlbumListFiltersDto $filters      Filters
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, AlbumListFiltersDto $filters): QueryBuilder
    {
        if ($filters->category instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters->category);
        }

        if ($filters->tag instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters->tag);
        }

        return $queryBuilder;
    }
}
