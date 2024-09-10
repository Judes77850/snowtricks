<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $path = null;

	#[ORM\ManyToOne(targetEntity: Tricks::class, inversedBy: 'images')]
	#[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
	private ?Tricks $trick = null;

	#[ORM\Column(type: 'boolean')]
	private bool $isMain = false;

	private ?File $file = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getPath(): ?string
	{
		return $this->path;
	}

	public function setPath(?string $path): static
	{
		$this->path = $path;

		return $this;
	}

	public function getIsMain(): bool
	{
		return $this->isMain;
	}

	public function setIsMain(bool $isMain): static
	{
		$this->isMain = $isMain;

		return $this;
	}

	public function getTrick(): ?Tricks
	{
		return $this->trick;
	}

	public function setTrick(?Tricks $trick): static
	{
		$this->trick = $trick;

		return $this;
	}

	/**
	 * @return File|null
	 */
	public function getFile(): ?File
	{
		return $this->file;
	}

	/**
	 * @param File|null $file
	 */
	public function setFile(?File $file): void
	{
		$this->file = $file;
	}
}

