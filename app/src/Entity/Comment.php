<?php

/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Content.
     */
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 1000)]
    private ?string $content = null;

    /**
     * Created at.
     */
    #[ORM\Column]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Album.
     */
    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'album_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Album $album = null;

    /**
     * User.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    /**
     * Guest email.
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email]
    private ?string $guestEmail = null;

    /**
     * Guest nickname.
     */
    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $guestNickname = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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
     * Getter for content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string $content Content
     *
     * @return $this
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for created at.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable $createdAt Created at
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for album.
     *
     * @return Album|null Album
     */
    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    /**
     * Setter for album.
     *
     * @param Album|null $album Album
     *
     * @return $this
     */
    public function setAlbum(?Album $album): static
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Getter for user.
     *
     * @return User|null User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for user.
     *
     * @param User|null $user User
     *
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Getter for guest email.
     *
     * @return string|null Guest email
     */
    public function getGuestEmail(): ?string
    {
        return $this->guestEmail;
    }

    /**
     * Setter for guest email.
     *
     * @param string|null $guestEmail Guest email
     *
     * @return $this
     */
    public function setGuestEmail(?string $guestEmail): static
    {
        $this->guestEmail = $guestEmail;

        return $this;
    }

    /**
     * Getter for guest nickname.
     *
     * @return string|null Guest nickname
     */
    public function getGuestNickname(): ?string
    {
        return $this->guestNickname;
    }

    /**
     * Setter for guest nickname.
     *
     * @param string|null $guestNickname Guest nickname
     *
     * @return $this
     */
    public function setGuestNickname(?string $guestNickname): static
    {
        $this->guestNickname = $guestNickname;

        return $this;
    }

    /**
     * Get author name.
     *
     * @return string Author name
     */
    public function getAuthorName(): string
    {
        if ($this->user instanceof \App\Entity\User) {
            return $this->user->getNickname();
        }

        return $this->guestNickname ?? 'Anonymous';
    }
}
