<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	#[Route('/', name: 'home', methods: ['GET'])]
	public function index(Request $request, TricksRepository $tricksRepository): Response
	{
		$limit = 5;
		$offset = $request->query->getInt('offset', 0);

		$tricks = $tricksRepository->findTricksWithPagination($offset, $limit);
		$hasMoreTricks = $tricksRepository->hasMoreTricks($offset + $limit, 1);

		return $this->render('index.html.twig', [
			'tricks' => $tricks,
			'hasMoreTricks' => $hasMoreTricks,
			'offset' => $offset + $limit,
		]);
	}

	#[Route('/tricks/load-more', name: 'tricks_load_more', methods: ['GET'])]
	public function loadMore(Request $request, TricksRepository $tricksRepository): JsonResponse
	{
		try {
			$offset = $request->query->getInt('offset', 0);
			$limit = 5;

			$tricks = $tricksRepository->findTricksWithPagination($offset, $limit);
			$hasMoreTricks = $tricksRepository->hasMoreTricks($offset + $limit, 1);

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

