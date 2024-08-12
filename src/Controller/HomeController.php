<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
	#[Route('/', name: 'home', methods: ['GET'])]
	public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
	{


		$query = $entityManager->getRepository(Tricks::class)->createQueryBuilder('t')
			->orderBy('t.createdAt', 'DESC')
			->getQuery();


		$tricks = $paginator->paginate(
			$query,
			$request->query->getInt('page', 1),
			9
		);

		return $this->render('index.html.twig', [
			'controller_name' => 'HomeController',
			'tricks' => $tricks,
		]);
	}
}
