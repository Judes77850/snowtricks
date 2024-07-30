<?php
namespace App\Security\Voter;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{

	protected function supports(string $attribute, mixed $subject): bool
	{
		return $subject instanceof Comment;
	}

	protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
	{
		$user = $token->getUser();
		$comment = $subject;
		if (!$user instanceof UserInterface) {
			return false;
		}
		if ($user !== $comment->getAuthorId()) {
			return false;
		}
		return true;

	}
}
