<?php

/**
 * User entity.
 */

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class User.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'validators.thisEmailAlreadyExists')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Email.
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * Roles.
     *
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Hashed password.
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private ?string $password = null;

    /**
     * Favorite albums.
     */
    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'favoritedBy')]
    #[ORM\JoinTable(name: 'user_favorites')]
    private Collection $favoriteAlbums;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->favoriteAlbums = new ArrayCollection();
    }

    /**
     * Getter for favorite albums.
     *
     * @return Collection<int, Album> Favorite albums collection
     */
    public function getFavoriteAlbums(): Collection
    {
        return $this->favoriteAlbums;
    }

    /**
     * Add album to favorites.
     *
     * @param Album $album Album entity
     *
     * @return $this
     */
    public function addFavoriteAlbum(Album $album): static
    {
        if (!$this->favoriteAlbums->contains($album)) {
            $this->favoriteAlbums->add($album);
        }

        return $this;
    }

    /**
     * Remove album from favorites.
     *
     * @param Album $album Album entity
     *
     * @return $this
     */
    public function removeFavoriteAlbum(Album $album): static
    {
        $this->favoriteAlbums->removeElement($album);

        return $this;
    }

    /**
     * Check if album is favorited.
     *
     * @param Album $album Album entity
     *
     * @return bool True if favorited, false otherwise
     */
    public function hasFavorited(Album $album): bool
    {
        return $this->favoriteAlbums->contains($album);
    }

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email Email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     *
     * @return string User identifier
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter for roles.
     *
     * @see UserInterface
     *
     * @return array<int, string> Roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRole::ROLE_USER->value;

        return array_unique($roles);
    }

    /**
     * Setter for roles.
     *
     * @param array<int, string> $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Getter for password.
     *
     * @see PasswordAuthenticatedUserInterface
     *
     * @return string|null Password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter for password.
     *
     * @param string $password User password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Removes sensitive information from the token.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
