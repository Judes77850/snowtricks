<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TricksRepository::class)]
class Tricks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

	#[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'trickId', cascade: ['persist'])]
	private ?Collection $media = null;

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


    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'trickId')]
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
	    $this->media = new ArrayCollection();
		$this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

	public function getMedia(): Collection
	{
		return $this->media;
	}

	public function addMedia(Media $media): static
	{
		if (!$this->media->contains($media)) {
			$this->media->add($media);
			$media->setTrickId($this);
		}

		return $this;
	}

	public function removeMedia(Media $media): static
	{
		if ($this->media->removeElement($media)) {
			if ($media->getTrickId() === $this) {
				$media->setTrickId(null);
			}
		}

		return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
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
}
