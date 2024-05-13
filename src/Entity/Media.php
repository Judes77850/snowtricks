<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column]
    private ?bool $isVideo = null;

    #[ORM\ManyToOne]
    private ?Tricks $trickId = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    private ?User $userId = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    private ?Categories $categoryId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function isIsVideo(): ?bool
    {
        return $this->isVideo;
    }

    public function setIsVideo(bool $isVideo): static
    {
        $this->isVideo = $isVideo;

        return $this;
    }

    public function getTrickId(): ?Tricks
    {
        return $this->trickId;
    }

    public function setTrickId(?Tricks $trickId): static
    {
        $this->trickId = $trickId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCategoryId(): ?Categories
    {
        return $this->categoryId;
    }

    public function setCategoryId(?Categories $categoryId): static
    {
        $this->categoryId = $categoryId;

        return $this;
    }
}
