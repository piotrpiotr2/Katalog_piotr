<?php

/**
 * Album repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Album;
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
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('album')
            ->select(
                'partial album.{id, createdAt, updatedAt, title}',
                'partial category.{id, title}'
            )
            ->join('album.category', 'category');
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
}
