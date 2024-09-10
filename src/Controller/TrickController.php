<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Image;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CategoriesRepository;
use App\Repository\CommentRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private Security $security;

	public function __construct(EntityManagerInterface $entityManager, Security $security)
	{
		$this->entityManager = $entityManager;
		$this->security = $security;
	}

	#[Route('/trick/new', name: 'trick_new', methods: ['GET', 'POST'])]
	public function new(Request $request, SluggerInterface $slugger, FileUploader $fileUploader): Response
	{
		$trick = new Tricks();
		$trick->setCreatedAt(new \DateTimeImmutable());

		$user = $this->security->getUser();
		$trick->setAuthorId($user);

		$form = $this->createForm(TrickType::class, $trick, ['validation_groups' => 'new']);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$fileUploader->uploadImages($trick);
			$fileUploader->uploadVideos($trick);

			$mainImageSet = false;
			foreach ($trick->getImages() as $image) {
				if ($image->getIsMain()) {
					$trick->setIsMain($image);
					$mainImageSet = true;
					break;
				}
			}

			if (!$mainImageSet && $trick->getImages()->count() > 0) {
				$trick->setIsMain($trick->getImages()->first());
			}

			$trick->setSlug($slugger->slug($trick->getName())->lower());

			$this->entityManager->persist($trick);
			$this->entityManager->flush();

			$this->addFlash('success', 'Trick créé avec succès.');

			return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()]);
		}

		return $this->render('trick/new.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/trick/{slug}', name: 'trick_show', methods: ['GET', 'POST'])]
	#[IsGranted('IS_AUTHENTICATED_FULLY')]
	public function show(string $slug, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
	{
		// Récupére le trick par son slug
		$trick = $entityManager->getRepository(Tricks::class)->findOneBy(['slug' => $slug]);

		if (!$trick) {
			throw $this->createNotFoundException('Trick not found');
		}

		// Crée et traite le formulaire de commentaire
		$comment = new Comment();
		$form = $this->createForm(CommentType::class, $comment);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$comment->setTrickId($trick);
			$comment->setCreatedAt(new \DateTimeImmutable());
			$comment->setAuthorId($this->getUser());

			$entityManager->persist($comment);
			$entityManager->flush();

			return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()]);
		}

		// Récupére les commentaires associés au trick
		$queryBuilder = $entityManager->getRepository(Comment::class)->createQueryBuilder('c')
			->where('c.trickId = :trickId')
			->setParameter('trickId', $trick->getId())
			->orderBy('c.createdAt', 'DESC');

		$pagination = $paginator->paginate(
			$queryBuilder,
			$request->query->getInt('page', 1),
			9 // Nombre de commentaires par page
		);

		$totalItemCount = $pagination->getTotalItemCount();
		$currentPage = $pagination->getCurrentPageNumber();
		$itemsPerPage = $pagination->getItemNumberPerPage();

		$hasMoreComments = $totalItemCount > $itemsPerPage * $currentPage;

		return $this->render('trick/show.html.twig', [
			'trick' => $trick,
			'comments' => $pagination,
			'form' => $form->createView(),
			'has_more_comments' => $hasMoreComments, // Assurez-vous que cette variable est passée au template
		]);
	}



	#[Route('/tricks', name: 'tricks_index', methods: ['GET'])]
	public function index(Request $request, CategoriesRepository $categoriesRepository): Response
	{
		$categoryId = $request->query->get('category', null);

		$tricksRepository = $this->entityManager->getRepository(Tricks::class);

		if ($categoryId) {
			$category = $categoriesRepository->find($categoryId);
			$tricks = $tricksRepository->findBy(['categoryId' => $category]);
		} else {
			$tricks = $tricksRepository->findAll();
		}

		$categories = $categoriesRepository->findAll();

		return $this->render('trick/index.html.twig', [
			'tricks' => $tricks,
			'categories' => $categories,
			'selectedCategory' => $categoryId,
		]);
	}


	#[Route('/trick/{id}/edit', name: 'trick_edit', methods: ['GET', 'POST'])]
	public function edit(Request $request, int $id, FileUploader $fileUploader): Response
	{
		$trick = $this->entityManager->getRepository(Tricks::class)->find($id);
		$this->denyAccessUnlessGranted('', $trick);

		if (!$trick) {
			throw $this->createNotFoundException('Trick not found');
		}

		$form = $this->createForm(TrickType::class, $trick);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$fileUploader->uploadImages($trick);
			$fileUploader->uploadVideos($trick);

			$mainImageSet = false;
			foreach ($trick->getImages() as $image) {
				if ($image->getIsMain()) {
					$trick->setMainImage($image);
					$mainImageSet = true;
					break;
				}
			}

			if (!$mainImageSet && $trick->getImages()->count() > 0) {
				$trick->setMainImage($trick->getImages()->first());
			}

			$this->entityManager->flush();

			$this->addFlash('success', 'Trick modifié avec succès.');

			return $this->redirectToRoute('account_tricks');
		}

		return $this->render('trick/edit.html.twig', [
			'trick' => $trick,
			'form' => $form->createView(),
		]);
	}

	#[Route('/trick/{id}/delete', name: 'trick_delete', methods: ['POST'])]
	public function deleteTrick(Request $request, int $id): Response
	{
		$trick = $this->entityManager->getRepository(Tricks::class)->find($id);

		if (!$trick) {
			throw $this->createNotFoundException('Trick non trouvé');
		}

		$this->denyAccessUnlessGranted('delete', $trick);

		if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
			$this->entityManager->remove($trick);
			$this->entityManager->flush();

			$this->addFlash('success', 'Trick supprimé avec succès.');
		}

		return $this->redirectToRoute('tricks_index');
	}
	#[Route('/comment/{id}/delete', name: 'comment_delete', methods: ['POST'])]
	public function deleteComment(Request $request, int $id, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
	{
		$comment = $commentRepository->find($id);

		if (!$comment) {
			throw $this->createNotFoundException('Commentaire non trouvé');
		}

		$this->denyAccessUnlessGranted('delete', $comment);

		$token = $request->request->get('_token');
		if (!$this->isCsrfTokenValid('delete' . $id, $token)) {
			throw $this->createAccessDeniedException('Token CSRF invalide');
		}

		$entityManager->remove($comment);
		$entityManager->flush();

		$this->addFlash('success', 'Commentaire supprimé avec succès.');

		return $this->redirectToRoute('trick_show', ['slug' => $comment->getTrickId()->getSlug()]);
	}

	#[Route('/trick/{id}/comments/load-more', name: 'trick_comments_load_more', methods: ['GET'])]
	public function loadMoreComments(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
	{
		$offset = $request->query->getInt('offset', 0);
		$limit = 10;

		$trick = $entityManager->getRepository(Tricks::class)->find($id);
		if (!$trick) {
			return new JsonResponse(['error' => 'Trick non trouvé'], 404);
		}

		$comments = $entityManager->getRepository(Comment::class)
			->createQueryBuilder('c')
			->where('c.trickId = :trickId')
			->setParameter('trickId', $trick->getId())
			->orderBy('c.createdAt', 'DESC')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getQuery()
			->getResult();

		$hasMoreComments = $entityManager->getRepository(Comment::class)
				->createQueryBuilder('c')
				->where('c.trickId = :trickId')
				->setParameter('trickId', $trick->getId())
				->orderBy('c.createdAt', 'DESC')
				->setFirstResult($offset + $limit)
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult() !== null;

		$html = $this->renderView('trick/comment_card.html.twig', [
			'comments' => $comments,
		]);

		return new JsonResponse([
			'content' => $html,
			'nextOffset' => $offset + $limit,
			'hasMoreComments' => $hasMoreComments,
		]);
	}
}
