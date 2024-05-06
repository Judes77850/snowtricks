<?php

namespace App\DataFixtures;

use App\Entity\Users;
use App\Entity\Categories;
use App\Entity\Tricks;
use App\Entity\Comment;
use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\SlugifyService;
use App\Service\TokenGeneratorService;

class GenerateFixtures extends Fixture
{
	private UserPasswordEncoderInterface $passwordEncoder;
	private SlugifyService $slugifyService;
	private TokenGeneratorService $tokenGeneratorService;

	public function __construct(
		UserPasswordEncoderInterface $passwordEncoder,
		SlugifyService               $slugifyService,
		TokenGeneratorService        $tokenGeneratorService
	)
	{
		$this->passwordEncoder = $passwordEncoder;
		$this->slugifyService = $slugifyService;
		$this->tokenGeneratorService = $tokenGeneratorService;
	}

	public function load(ObjectManager $manager)
	{
		// Chargement users depuis JSON
		$usersData = json_decode(file_get_contents(__DIR__ . '/usersData.json'), true);
		foreach ($usersData as $userData) {
			$user = $this->createUser($userData);
			$manager->persist($user);
		}
		$manager->flush();

		// Chargement trick categories depuis JSON
		$trickCategoriesData = json_decode(file_get_contents(__DIR__ . '/trickCategoriesData.json'), true);
		foreach ($trickCategoriesData as $categoryData) {
			$category = $this->createCategory($categoryData);
			$manager->persist($category);
		}
		$manager->flush();

		// Chargement tricks depuis JSON
		$tricksData = json_decode(file_get_contents(__DIR__ . '/tricksData.json'), true);
		foreach ($tricksData as $trickData) {
			$trick = $this->createTrick($trickData, $manager);
			$manager->persist($trick);
		}
		$manager->flush();

		// Chargement comments depuis JSON
		$commentsData = json_decode(file_get_contents(__DIR__ . '/commentsData.json'), true);
		foreach ($commentsData as $commentData) {
			$comment = $this->createComment($commentData, $manager);
			$manager->persist($comment);
		}
		$manager->flush();

		$mediaData = json_decode(file_get_contents(__DIR__ . '/mediaData.json'), true);

		foreach ($mediaData as $mediaItem) {
			$media = $this->createMedia(
				$mediaItem['trick_id'],
				$mediaItem['user_id'],
				$mediaItem['category_id'],
				$mediaItem['path'],
				$mediaItem['is_video']
			);
			$manager->persist($media);
		}
		$manager->flush();
	}

	private function createUser(array $userData): Users
	{
		$user = new User();
		$user->setEmail($userData['email'])
			->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']))
			->setFirstname($userData['firstName'])
			->setLastname($userData['lastName'])
			->setToken($this->tokenGeneratorService->generateToken());

		return $user;
	}

	private function createCategory(array $categoryData): Categories
	{
		$category = new Category();
		$category->setId($categoryData['category_id'])
			->setName($categoryData['name']);

		return $category;
	}

	private function createTrick(array $trickData, ObjectManager $manager): Tricks
	{
		$trick = new Trick();
		$trick->setName($trickData['name'])
			->setDescription($trickData['description'])
			->setCategory($manager->getReference(Category::class, $trickData['category_id']))
			->setUser($manager->getReference(User::class, $trickData['author_id']))
			->setSlug($this->slugifyService->slugify($trickData['name']));

		return $trick;
	}

	private function createComment(array $commentData, ObjectManager $manager): Comment
	{
		$comment = new Comment();
		$comment->setContent($commentData['content'])
			->setUser($manager->getReference(User::class, $commentData['author_id']))
			->setTrick($manager->getReference(Trick::class, $commentData['trick_id']));

		return $comment;
	}

	private function createMedia(int $trickId, int $userId, int $categoryId, string $path, bool $isVideo): Media
	{
		$media = new Media();
		$media->setTrickId($trickId)
			->setUserId($userId)
			->setCategoryId($categoryId)
			->setPath($path)
			->setIsVideo($isVideo);

		return $media;
	}
}
