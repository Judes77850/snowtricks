<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfilePicture;
use App\Form\UserType;
use App\Form\ProfilePictureType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\FileUploader;


class AccountController extends AbstractController
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	#[Route('/account', name: 'account_index', methods: ['GET'])]
	public function index(): Response
	{
		$user = $this->getUser();

		if (!$user instanceof User) {
			throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
		}

		$profilePicture = $user->getProfilePicture();

		return $this->render('account/index.html.twig', [
			'user' => $user,
			'profilePicture' => $profilePicture,
		]);
	}

	#[Route('/account/edit', name: 'account_edit', methods: ['GET', 'POST'])]
	#[IsGranted('IS_AUTHENTICATED_FULLY')]
	public function edit(Request $request, UserPasswordHasherInterface $passwordHasher): Response
	{
		$user = $this->getUser();

		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$plainPassword = $form->get('plainPassword')->getData();
			if ($plainPassword) {
				$hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
				$user->setPassword($hashedPassword);
			}

			$this->entityManager->flush();

			$this->addFlash('success', 'Profil mis à jour avec succès.');

			return $this->redirectToRoute('account_index');
		}

		return $this->render('account/edit.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/account/edit-profile-picture', name: 'edit_profile_picture', methods: ['GET', 'POST'])]
	public function editProfilePicture(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
	{

		$user = $this->getUser();
		if (!$user) {
			throw $this->createAccessDeniedException('You must be logged in to edit your profile picture.');
		}
		$profilePicture = $user->getProfilePicture() ?? new UserProfilePicture();

		$form = $this->createForm(ProfilePictureType::class, $profilePicture);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$file = $form->get('file')->getData();
			if ($file) {
				$filename = $fileUploader->uploadProfilePicture($file);
				$profilePicture->setFilename($filename);
			}

			$profilePicture->setUser($user);
			$user->setProfilePicture($profilePicture);
			$this->entityManager->persist($profilePicture);
			$this->entityManager->flush();

			$this->addFlash('success', 'Profile picture updated successfully.');

			return $this->redirectToRoute('edit_profile_picture');
		}

		return $this->render('account/edit_profile_picture.html.twig', [
			'form' => $form->createView(),
		]);
	}


	#[Route('/account/tricks', name: 'account_tricks', methods: ['GET'])]
	public function myTricks(): Response
	{
		$user = $this->getUser();

		$tricks = $user->getTricks();

		return $this->render('account/my_tricks.html.twig', [
			'tricks' => $tricks,
		]);
	}
}
