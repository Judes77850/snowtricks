<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;


	#[ORM\Column(length: 255, unique: true)]
	private ?string $email = null;

	#[ORM\Column]
	private ?\DateTimeImmutable $createdAt = null;

	#[ORM\Column(length: 255)]
	private ?string $password = null;

	#[ORM\Column(length: 255)]
	private ?string $userName = null;

	#[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'userId')]
	private Collection $media;

	#[ORM\OneToMany(targetEntity: Tricks::class, mappedBy: 'authorId')]
	private Collection $tricks;

	#[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'authorId')]
	private Collection $comments;

	#[ORM\Column]
	private ?bool $isVerified = false;

	#[ORM\Column(type: 'uuid', nullable: true)]
	private ?Uuid $token = null;

	#[ORM\Column(type: 'json')]
	private array $roles = [];

	public function __construct()
	{
		$this->media = new ArrayCollection();
		$this->tricks = new ArrayCollection();
		$this->createdAt = new \DateTimeImmutable();
		$this->comments = new ArrayCollection();
		$this->roles = [];
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

	public function getCreatedAt(): ?\DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTimeImmutable $createdAt): static
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(string $password): static
	{
		$this->password = $password;

		return $this;
	}

	public function getUserName(): ?string
	{
		return $this->userName;
	}

	public function setUserName(string $userName): static
	{
		$this->userName = $userName;

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
			$medium->setUserId($this);
		}

		return $this;
	}

	public function removeMedium(Media $medium): static
	{
		if ($this->media->removeElement($medium)) {
			// set the owning side to null (unless already changed)
			if ($medium->getUserId() === $this) {
				$medium->setUserId(null);
			}
		}

		return $this;
	}


	/**
	 * @return Collection<int, Tricks>
	 */
	public function getTricks(): Collection
	{
		return $this->tricks;
	}

	public function addTrick(Tricks $trick): static
	{
		if (!$this->tricks->contains($trick)) {
			$this->tricks->add($trick);
			$trick->setAuthorId($this);
		}

		return $this;
	}

	public function removeTrick(Tricks $trick): static
	{
		if ($this->tricks->removeElement($trick)) {
			// set the owning side to null (unless already changed)
			if ($trick->getAuthorId() === $this) {
				$trick->setAuthorId(null);
			}
		}

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
			$comment->setAuthorId($this);
		}

		return $this;
	}

	public function removeComment(Comment $comment): static
	{
		if ($this->comments->removeElement($comment)) {
			// set the owning side to null (unless already changed)
			if ($comment->getAuthorId() === $this) {
				$comment->setAuthorId(null);
			}
		}

		return $this;
	}

	public function setRoles(?array $roles): static
	{
		$this->roles = $roles;

		return $this;
	}

	public function getRoles(): array
	{
		$roles = $this->roles;
		$roles[] = 'ROLE_USER';
		return array_unique($roles);
	}

	public function eraseCredentials()
	{
		// TODO: Implement eraseCredentials() method.
	}

	public function getUserIdentifier(): string
	{
		return $this->email;
	}

	public function isVerified(): ?bool
	{
		return $this->isVerified;
	}

	public function setIsVerified(bool $isVerified): static
	{
		$this->isVerified = $isVerified;

		return $this;
	}

	public function getToken(): ?Uuid
	{
		return $this->token;
	}

	public function setToken(?Uuid $token): static
	{
		$this->token = $token;

		return $this;
	}
}

