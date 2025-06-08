<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnailURL = null;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')] 
    private ?Release $album = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getThumbnailURL(): ?string
    {
        return $this->thumbnailURL;
    }

    public function setThumbnailURL(?string $thumbnailURL): static
    {
        $this->thumbnailURL = $thumbnailURL;

        return $this;
    }

    public function getAlbum(): ?Release
    {
        return $this->album;
    }

    public function setAlbum(?Release $album): static
    {
        $this->album = $album;

        return $this;
    }

}
