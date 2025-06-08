<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Artiste;
use App\Entity\Release;
use App\Entity\Track;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Artiste::class)]
    private Collection $favoriteArtistes;

    #[ORM\ManyToMany(targetEntity: Release::class)]
    private Collection $favoriteReleases;

    #[ORM\ManyToMany(targetEntity: Track::class)]
    private Collection $favoriteTracks;

    public function __construct()
    {
        $this->favoriteArtistes = new ArrayCollection();
        $this->favoriteReleases = new ArrayCollection();
        $this->favoriteTracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFavoriteArtistes(): Collection
    {
        return $this->favoriteArtistes;
    }

    public function addFavoriteArtiste(Artiste $artiste): self
    {
        if (!$this->favoriteArtistes->contains($artiste)) {
            $this->favoriteArtistes[] = $artiste;
        }

        return $this;
    }

    public function removeFavoriteArtiste(Artiste $artiste): self
    {
        $this->favoriteArtistes->removeElement($artiste);

        return $this;
    }

    public function getFavoriteReleases(): Collection
    {
        return $this->favoriteReleases;
    }

    public function addFavoriteRelease(Release $release): self
    {
        if (!$this->favoriteReleases->contains($release)) {
            $this->favoriteReleases[] = $release;
        }

        return $this;
    }

    public function removeFavoriteRelease(Release $release): self
    {
        $this->favoriteReleases->removeElement($release);

        return $this;
    }

    public function getFavoriteTracks(): Collection
    {
        return $this->favoriteTracks;
    }

    public function addFavoriteTrack(Track $track): self
    {
        if (!$this->favoriteTracks->contains($track)) {
            $this->favoriteTracks[] = $track;
        }

        return $this;
    }

    public function removeFavoriteTrack(Track $track): self
    {
        $this->favoriteTracks->removeElement($track);

        return $this;
    }
}
