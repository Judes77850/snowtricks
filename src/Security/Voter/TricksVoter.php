<?php

namespace App\Security\Voter;

use App\Entity\Tricks;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TricksVoter extends Voter
{

	protected function supports(string $attribute, mixed $subject): bool
	{
		return $subject instanceof Tricks;
	}

	protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
	{
		$user = $token->getUser();
		$trick = $subject;
		if (!$user instanceof UserInterface) {
			return false;
		}
		if ($user !== $trick->getAuthorId()) {
			return false;
		}
		return true;

	}
}
