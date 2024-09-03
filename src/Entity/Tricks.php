<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TricksRepository::class)]
#[UniqueEntity(fields: ['name', 'slug'], message: 'Cette figure existe déjà', groups: ['new', 'edit'])]
class Tricks
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;


	#[ORM\Column(length: 255)]
	private ?string $name = null;

	#[ORM\Column(type: Types::TEXT)]
	private ?string $description = null;

	#[ORM\Column(length: 255)]
	private ?string $slug = null;

	#[ORM\Column]
	private ?\DateTimeImmutable $createdAt = null;

	#[ORM\ManyToOne(inversedBy: 'tricks')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Categories $categoryId = null;

	#[ORM\ManyToOne(inversedBy: 'tricks')]
	#[ORM\JoinColumn(nullable: false)]
	private ?User $authorId = null;


	#[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'trickId', cascade: ['remove'])]
	private Collection $comments;

	#[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'trick', cascade: ['persist', 'remove'], orphanRemoval: true)]
	private Collection $media;

	#[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'trick', cascade: ['persist', 'remove'], orphanRemoval: true)]
	private Collection $images;

	#[ORM\ManyToOne(targetEntity: Image::class, inversedBy: 'tricks')]
	#[ORM\JoinColumn(nullable: true)]
	private ?Image $mainImage = null;

	#[ORM\OneToMany(targetEntity: Video::class, mappedBy: 'trick' , cascade: ['persist', 'remove'], orphanRemoval: true)]
	private Collection $videos;

	public function __construct()
	{
		$this->comments = new ArrayCollection();
		$this->media = new ArrayCollection();
		$this->createdAt = new \DateTimeImmutable();
		$this->images = new ArrayCollection();
		$this->videos = new ArrayCollection();
	}

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

	public function __toString(): string
	{
		return $this->name ?? '';
	}


	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description): static
	{
		$this->description = $description;

		return $this;
	}

	public function setMainImage(?Image $image): static
	{
		$this->mainImage = $image;

		return $this;
	}

	public function getMainImage(): ?Image
	{
		return $this->mainImage;
	}

	public function getSlug(): ?string
	{
		return $this->slug;
	}

	public function setSlug(string $slug): static
	{
		$this->slug = $slug;

		return $this;
	}

	public function getCreatedAt(): ?\DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTimeImmutable $createdAt): static
	{
		$this->createdAt = $createdAt;

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

	public function getAuthorId(): ?User
	{
		return $this->authorId;
	}

	public function setAuthorId(?User $authorId): static
	{
		$this->authorId = $authorId;

		return $this;
	}

	/**
	 * @return Collection<int, Comment>
	 */
	public function getComments(): Collection
	{
		return $this->comments;
	}

	public function addComment(Comment $comment): static
	{
		if (!$this->comments->contains($comment)) {
			$this->comments->add($comment);
			$comment->setTrickId($this);
		}

		return $this;
	}

	public function removeComment(Comment $comment): static
	{
		if ($this->comments->removeElement($comment)) {
			if ($comment->getTrickId() === $this) {
				$comment->setTrickId(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Media>
	 */
	public function getMedia(): Collection
	{
		return $this->media;
	}

	public function addMedium(Media $medium): static
	{
		if (!$this->media->contains($medium)) {
			$this->media->add($medium);
			$medium->setTrick($this);
		}

		return $this;
	}

	public function removeMedium(Media $medium): static
	{
		if ($this->media->removeElement($medium)) {
			if ($medium->getTrick() === $this) {
				$medium->setTrick(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Image>
	 */
	public function getImages(): Collection
	{
		return $this->images;
	}

	public function addImage(Image $image): static
	{
		if (!$this->images->contains($image)) {
			$this->images->add($image);
			$image->setTrick($this);
		}

		return $this;
	}

	public function removeImage(Image $image): static
	{
		if ($this->images->removeElement($image)) {
			if ($image->getTrick() === $this) {
				$image->setTrick(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Video>
	 */
	public function getVideos(): Collection
	{
		return $this->videos;
	}

	public function addVideo(Video $video): static
	{
		if (!$this->videos->contains($video)) {
			$this->videos->add($video);
			$video->setTrick($this);
		}

		return $this;
	}

	public function removeVideo(Video $video): static
	{
		if ($this->videos->removeElement($video)) {
			if ($video->getTrick() === $this) {
				$video->setTrick(null);
			}
		}

		return $this;
	}
}

