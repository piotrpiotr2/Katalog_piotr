<?php

/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Constructor.
     *
     * @param CommentRepository $commentRepository Comment repository
     */
    public function __construct(private readonly CommentRepository $commentRepository)
    {
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

    /**
     * Find by id.
     *
     * @param array $id Comment id
     *
     * @return Comment[] Array of Comment entities
     */
    public function findBy(array $id): array
    {
        return $this->commentRepository->findBy(['album' => $id]);
    }
}
