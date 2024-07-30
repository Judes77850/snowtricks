<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{
	#[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		if ($this->getUser()) {
			$this->addFlash('warning', 'Vous êtes déjà connecté.e.');

			return $this->redirectToRoute('home',
				[],
				Response::HTTP_SEE_OTHER
			);
		}

		return $this->render('auth/login.html.twig', [
			'last_username' => $authenticationUtils->getLastUsername(),
			'error' => $authenticationUtils->getLastAuthenticationError(),
		]);
	}

	#[Route('/forgot-password', name: 'app_forgot_password', methods: ['GET', 'POST'])]
	public function forgotPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
	{
		if ($request->isMethod('POST')) {

			$email = $request->request->get('email');
			$user = $userRepository->findOneBy(['email' => $email]);

			if ($user) {
				$token = Uuid::v4();

				$user->setToken($token);
				$userRepository->save($user, true);

				$email = (new Email())
					->from('reset_password@snowtricks.com')
					->to($user->getEmail())
					->subject('Réinitialisation de mot de passe')
					->html('<p>Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href="' . $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL) . '">Réinitialiser le mot de passe</a></p>');

				$mailer->send($email);

				$this->addFlash('success', 'Un email de réinitialisation de mot de passe a été envoyé à votre adresse email.');
				return $this->render('auth/forgot_password.html.twig', [
					'email' => $email,
				]);
			} else {
				$this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
			}

			return $this->redirectToRoute('app_login');
		}

		return $this->render('auth/forgot_password.html.twig');
	}

	#[Route('/reset-password/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
	public function resetPassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher, string $token): Response
	{

		$user = $userRepository->findOneBy(['token' => $token]);
		if(!$user){
			$this->addFlash('warning', 'Une erreur est survenue, merci de reessayer');
			return $this->redirectToRoute('app_forgot_password');
		}

		if ($request->isMethod('POST')) {

			$password = $request->request->get('password');
			$encodedPassword = $hasher->hashPassword($user, $password);

			$user->setPassword($encodedPassword);
			$user->setToken(null);

			$userRepository->save($user, true);

			$this->addFlash('success', 'Mot de passe réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.');
			return $this->redirectToRoute('app_login');
		}

		return $this->render('auth/reset_password.html.twig', [
			'token' => $token,
		]);
	}


	#[Route('/logout', name: 'app_logout', methods: ['GET'])]
	public function logout(): never
	{
		throw new \Exception('Don\'t forget to activate logout in security.yaml');
	}
}
