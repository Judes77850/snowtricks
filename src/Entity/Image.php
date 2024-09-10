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

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tricks $trick = null;

	#[ORM\Column(type: 'boolean')]
	private bool $isMain = false;

	#[ORM\OneToMany(targetEntity: Tricks::class, mappedBy: 'mainImage')]
	private Collection $tricks;

	private ?File $file = null;

	public function __construct()
	{
		$this->tricks = new ArrayCollection();
	}

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

	public function getTricks(): Collection
	{
		return $this->tricks;
	}

	public function addTrick(Tricks $trick): static
	{
		if (!$this->tricks->contains($trick)) {
			$this->tricks->add($trick);
			$trick->setMainImage($this);
		}

		return $this;
	}

	public function removeTrick(Tricks $trick): static
	{
		if ($this->tricks->removeElement($trick)) {
			if ($trick->getMainImage() === $this) {
				$trick->setMainImage(null);
			}
		}

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
