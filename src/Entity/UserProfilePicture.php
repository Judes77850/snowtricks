<?php

namespace App\Entity;

use App\Repository\UserProfilePictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: UserProfilePictureRepository::class)]
class UserProfilePicture
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	private ?string $filename = null;

	private ?File $file = null;

	#[ORM\OneToOne(inversedBy: 'profilePicture', cascade: ['persist', 'remove'])]
	#[ORM\JoinColumn(unique: false, nullable: true)]
	private ?User $user = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getFilename(): ?string
	{
		return $this->filename;
	}

	public function setFilename(string $filename): static
	{
		$this->filename = $filename;

		return $this;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

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

	public function __serialize(): array
	{
		return [
			'id' => $this->id,
			'filename' => $this->filename,
		];
	}

	public function __unserialize(array $data): void
	{
		$this->id = $data['id'];
		$this->filename = $data['filename'];
	}
}
