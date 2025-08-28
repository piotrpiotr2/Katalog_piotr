<?php
/**
 * Album list input filters DTO.
 */

namespace App\Controller;

/**
 * Class AlbumListInputFiltersDto.
 */
class AlbumListInputFiltersDto
{
    /**
     * Constructor.
     *
     * @param int|null $categoryId Category identifier
     * @param int|null $tagId      Tag identifier
     */
    public function __construct(public readonly ?int $categoryId = null, public readonly ?int $tagId = null)
    {
    }
}