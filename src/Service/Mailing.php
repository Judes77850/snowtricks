<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailing
{
	public function __construct(
		private MailerInterface $mailer
	)
	{

	}

	public function sending(
		User $user, string $subject, string $template, string $link
	): void
	{
		$email = (new TemplatedEmail())
			->from('compte@snowtricks.fr')
			->to($user->getEmail())
			->subject($subject)
			->htmlTemplate($template)
			->context(['user' => $user, 'link' => $link]);

		$this->mailer->send($email);
	}
}
