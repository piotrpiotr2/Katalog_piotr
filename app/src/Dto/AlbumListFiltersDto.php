<?php

/**
 * Album list filters DTO.
 */

namespace App\Dto;

use App\Entity\Category;
use App\Entity\Tag;

/**
 * Class AlbumListFiltersDto.
 */
class AlbumListFiltersDto
{
    /**
     * Constructor.
     *
     * @param Tag|null      $tag      Tag entity
     */
    public function __construct(public readonly ?Category $category, public readonly ?Tag $tag)
    {
    }
}
