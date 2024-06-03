<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
	#[Route('/home', name: 'home')]
	public function index(Security $security): Response
	{
		$user = $security->getUser();
		$userName = $user?->getUserName();
		return $this->render('index.html.twig', [
			'controller_name' => 'HomeController',
			'userName' => $userName,
		]);
	}
}
