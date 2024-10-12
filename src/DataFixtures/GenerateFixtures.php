<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Categories;
use App\Entity\Tricks;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\SlugifyService;
use App\Service\TokenGeneratorService;

class GenerateFixtures extends Fixture
{
	private UserPasswordHasherInterface $passwordHasher;
	private SlugifyService $slugifyService;
	private TokenGeneratorService $tokenGeneratorService;

	public function __construct(
		UserPasswordHasherInterface $passwordHasher,
		SlugifyService              $slugifyService,
		TokenGeneratorService       $tokenGeneratorService
	)
	{
		$this->passwordHasher = $passwordHasher;
		$this->slugifyService = $slugifyService;
		$this->tokenGeneratorService = $tokenGeneratorService;
	}

	public function load(ObjectManager $manager): void
	{
		$usersData = json_decode(file_get_contents(__DIR__ . '/usersData.json'), true);
		$users = [];
		foreach ($usersData as $userData) {
			$user = $this->createUser($userData);
			$manager->persist($user);
			$users[] = $user;
		}
		$manager->flush();

		$categoriesData = json_decode(file_get_contents(__DIR__ . '/categoriesData.json'), true);
		$categories = [];
		foreach ($categoriesData as $categoryData) {
			$category = $this->createCategory($categoryData);
			$manager->persist($category);
			$categories[] = $category;
		}
		$manager->flush();

		$tricksData = json_decode(file_get_contents(__DIR__ . '/tricksData.json'), true);
		$tricks = [];
		foreach ($tricksData as $trickData) {
			$trick = $this->createTrick($trickData, $categories, $users);
			$manager->persist($trick);
			$tricks[] = $trick;
		}
		$manager->flush();

		$commentsData = json_decode(file_get_contents(__DIR__ . '/commentsData.json'), true);
		foreach ($commentsData as $commentData) {
			$comment = $this->createComment($commentData, $users, $tricks);
			$manager->persist($comment);
		}
		$manager->flush();

		$mediaData = json_decode(file_get_contents(__DIR__ . '/mediasData.json'), true);

		foreach ($mediaData as $mediaItem) {
			if ($mediaItem['is_video']) {
				$video = $this->createVideo($tricks, $mediaItem['path']);
				$manager->persist($video);
			} else {
				$image = $this->createImage($tricks, $mediaItem['path']);
				$manager->persist($image);
			}
		}
		$manager->flush();
	}

	private function createUser(array $userData): User
	{
		$user = new User();
		$user->setEmail($userData['email'])
			->setPassword($this->passwordHasher->hashPassword($user, $userData['password']))
			->setUserName($userData['user_name'])
			->setToken($this->tokenGeneratorService->generateToken())
			->setCreatedAt(new \DateTimeImmutable($userData['created_at']));
		return $user;
	}

	private function createCategory(array $categoryData): Categories
	{
		$category = new Categories();
		$category->setName($categoryData['name']);
		$category->setSlug($categoryData['slug']);
		return $category;
	}

	private function createTrick(array $trickData, array $categories, array $users): Tricks
	{
		$trick = new Tricks();
		$trick->setName($trickData['name'])
			->setDescription($trickData['description'])
			->setCategoryId($categories[array_rand($categories)])
			->setAuthorId($users[array_rand($users)])
			->setSlug($this->slugifyService->slugify($trickData['name']))
			->setCreatedAt(new \DateTimeImmutable($trickData['created_at']));

		return $trick;
	}

	private function createComment(array $commentData, array $users, array $tricks): Comment
	{
		$comment = new Comment();
		$comment->setContent($commentData['content'])
			->setAuthorId($users[array_rand($users)])
			->setTrickId($tricks[array_rand($tricks)])
			->setCreatedAt(new \DateTimeImmutable($commentData['created_at']));

		return $comment;
	}

	private function createImage(array $tricks, string $path): Image
	{
		$image = new Image();
		$image->setTrick($tricks[array_rand($tricks)])
			->setPath($path);

		return $image;
	}


	private function createVideo(array $tricks, string $url): Video
	{
		if (empty($url)) {
			throw new \Exception("L'URL de la vidéo ne peut pas être vide.");
		}

		$videoId = $this->extractYouTubeVideoId($url);
		if (!$videoId) {
			throw new \Exception("Impossible d'extraire l'ID de la vidéo YouTube.");
		}

		$video = new Video();
		$video->setTrick($tricks[array_rand($tricks)])
			->setUrl($url)
			->setVideoId($videoId);

		return $video;
	}

	private function extractYouTubeVideoId(string $url): ?string
	{
		preg_match('/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches);
		return $matches[1] ?? null;
	}
}
