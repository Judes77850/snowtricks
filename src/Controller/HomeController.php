<?php

namespace App\Controller;

use App\Entity\Tricks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	#[Route('/', name: 'home', methods: ['GET'])]
	public function index(Request $request, EntityManagerInterface $entityManager): Response
	{
		$limit = 5;
		$offset = $request->query->getInt('offset', 0);

		$tricks = $entityManager->getRepository(Tricks::class)
			->createQueryBuilder('t')
			->orderBy('t.createdAt', 'DESC')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getQuery()
			->getResult();

		$hasMoreTricks = $entityManager->getRepository(Tricks::class)
				->createQueryBuilder('t')
				->orderBy('t.createdAt', 'DESC')
				->setFirstResult($offset + $limit)
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult() !== null;

		return $this->render('index.html.twig', [
			'tricks' => $tricks,
			'hasMoreTricks' => $hasMoreTricks,
			'offset' => $offset + $limit,
		]);
	}

	#[Route('/tricks/load-more', name: 'tricks_load_more', methods: ['GET'])]
	public function loadMore(Request $request, EntityManagerInterface $entityManager): JsonResponse
	{
		try {
			$offset = $request->query->getInt('offset', 0);
			$limit = 5;

			$tricks = $entityManager->getRepository(Tricks::class)
				->createQueryBuilder('t')
				->orderBy('t.createdAt', 'DESC')
				->setFirstResult($offset)
				->setMaxResults($limit)
				->getQuery()
				->getResult();

			$hasMoreTricks = $entityManager->getRepository(Tricks::class)
					->createQueryBuilder('t')
					->orderBy('t.createdAt', 'DESC')
					->setFirstResult($offset + $limit)
					->setMaxResults(1)
					->getQuery()
					->getOneOrNullResult() !== null;

			$html = $this->renderView('trick/trick_card.html.twig', [
				'tricks' => $tricks,
			]);

			return new JsonResponse([
				'content' => $html,
				'nextOffset' => $offset + $limit,
				'hasMoreTricks' => $hasMoreTricks,
			]);
		} catch (\Exception $e) {
			return new JsonResponse([
				'error' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
				'message' => $e->getMessage(),
			], 500);
		}
	}

}

