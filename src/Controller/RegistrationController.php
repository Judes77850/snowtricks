<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\Mailing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class RegistrationController extends AbstractController
{

	#[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
	public function register(Request $request, UserPasswordHasherInterface $hasher, UserRepository $userRepository, Mailing $mailing): Response
	{
		$user = new User();

		$form = $this->createForm(RegistrationFormType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user->setRoles(['ROLES_USER']);
			$user->setToken(Uuid::v4());
			$user->setPassword(
				$hasher->hashPassword(
					$user,
					$form->get('password')->getData()
				)
			);

			$userRepository->save($user, true);
			$link = $this->generateUrl('app_registration_confirmation', ['token'=>$user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
			$mailing->sending($user, 'Confirmez votre inscription', 'mailing/registration_confirmation.html.twig', $link);

			$this->addFlash('success', 'Bravo ! Pour parfaire votre inscription, un email vous sera adressé.');
			return $this->redirectToRoute('app_login');
		}

		return $this->render('auth/register.html.twig', [
			'registrationForm' => $form->createView(),
		]);

	}

	#[Route('/register/confirmation/{token}', name: 'app_registration_confirmation', methods: ['GET'])]
	public function accountConfirmation(UserRepository $userRepository, string $token): Response
	{
		$user = $userRepository->findOneBy(['token' => $token]);

		if (!$user) {
			$this->addFlash('danger', 'Le lien de confirmation est invalide.');
			return $this->redirectToRoute('app_register');
		}

		if ($user->isVerified()) {
			$this->addFlash('info', 'Votre compte est déjà vérifié.');
			return $this->redirectToRoute('app_login');
		}

		$user->setIsVerified(true);
		$user->setToken(null);

		$userRepository->save($user, true);

		$this->addFlash('success', 'Votre compte a été vérifié avec succès. Vous pouvez maintenant vous connecter.');
		return $this->redirectToRoute('app_login');
	}

}
