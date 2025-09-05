<?php

/**
 * Tag entity.
 */

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
class Tag implements \Stringable
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title.
     */
    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 100)]
    private ?string $title = null;

    /**
     * Albums.
     */
    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'tags')]
    private Collection $albums;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->albums = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for albums.
     *
     * @return Collection<int, Album> Albums collection
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    /**
     * Add album to tag.
     *
     * @param Album $album Album entity
     *
     * @return $this
     */
    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->addTag($this);
        }

        return $this;
    }

    /**
     * Remove album from tag.
     *
     * @param Album $album Album entity
     *
     * @return $this
     */
    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeTag($this);
        }

        return $this;
    }

    /**
     * To string method.
     *
     * @return string Title
     */
    public function __toString(): string
    {
        return $this->title ?? '';
    }
}
