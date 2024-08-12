<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Image;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CategoriesRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
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

			$trick->setSlug($slugger->slug($trick->getName())->lower());

			$this->entityManager->persist($trick);

			$this->entityManager->flush();

			$this->addFlash('success', 'Trick créé avec succès.');

			return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
		}

		return $this->render('trick/new.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/trick/{id}', name: 'trick_show', methods: ['GET', 'POST'])]
	#[IsGranted('IS_AUTHENTICATED_FULLY')]
	public function show(int $id, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
	{
		$trick = $entityManager->getRepository(Tricks::class)->find($id);

		if (!$trick) {
			throw $this->createNotFoundException('Trick not found');
		}

		$comment = new Comment();
		$form = $this->createForm(CommentType::class, $comment);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$comment->setTrickId($trick);
			$comment->setCreatedAt(new \DateTimeImmutable());
			$comment->setAuthorId($this->getUser());

			$entityManager->persist($comment);
			$entityManager->flush();

			return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
		}

		$queryBuilder = $entityManager->getRepository(Comment::class)->createQueryBuilder('c')
			->where('c.trickId = :trickId')
			->setParameter('trickId', $trick->getId())
			->orderBy('c.createdAt', 'DESC');

		$pagination = $paginator->paginate(
			$queryBuilder,
			$request->query->getInt('page', 1),
			9
		);

		return $this->render('trick/show.html.twig', [
			'trick' => $trick,
			'comments' => $pagination,
			'form' => $form->createView(),
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
	public function deleteComment(Request $request, int $id): Response
	{
		$comment = $this->entityManager->getRepository(Comment::class)->find($id);

		if (!$comment) {
			throw $this->createNotFoundException('Commentaire non trouvé');
		}

		$this->denyAccessUnlessGranted('delete', $comment);

		$token = $request->request->get('_token');
		if ($this->isCsrfTokenValid('delete' . $id, $token)) {
			$this->entityManager->remove($comment);
			$this->entityManager->flush();

			$this->addFlash('success', 'Commentaire supprimé avec succès.');
		} else {
			throw new AccessDeniedException('Invalid CSRF token.');
		}

		return $this->redirectToRoute('trick_show', ['id' => $comment->getTrickId()->getId()]);
	}
}
