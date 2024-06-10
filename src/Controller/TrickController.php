<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Media;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	#[Route('/trick/new', name: 'trick_new', methods: ['GET', 'POST'])]
	public function new(Request $request, SluggerInterface $slugger): Response
	{
		$trick = new Tricks();
		$trick->setCreatedAt(new \DateTimeImmutable());
		$form = $this->createForm(TrickType::class, $trick);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$mediaCollection = $form->get('media')->getData();
			foreach ($mediaCollection as $mediaForm) {
				/** @var UploadedFile $file */
				$file = $mediaForm->get('path')->getData();
				if ($file) {
					$newFilename = uniqid().'.'.$file->guessExtension();
					$file->move(
						$this->getParameter('media_directory'),
						$newFilename
					);

					$media = new Media();
					$media->setPath($newFilename);
					$media->setIsVideo($mediaForm->get('isVideo')->getData());
					$media->setTrickId($trick);
					$trick->addMedia($media);
				}
			}

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

	#[Route('/trick/{id}', name: 'trick_show', methods: ['GET'])]
	public function show(int $id): Response
	{
		$trick = $this->entityManager->getRepository(Tricks::class)->find($id);

		if (!$trick) {
			throw $this->createNotFoundException('Trick not found');
		}

		return $this->render('trick/show.html.twig', [
			'trick' => $trick,
		]);
	}
}
