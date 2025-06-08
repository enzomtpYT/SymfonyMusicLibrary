<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/artiste')]
class ArtisteController extends AbstractController
{
    #[Route('/', name: 'app_artiste_index', methods: ['GET'])]
    public function index(ArtisteRepository $artisteRepository): Response
    {
        return $this->render('artiste/index.html.twig', [
            'artistes' => $artisteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_artiste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($artiste);
            $entityManager->flush();

            return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('artiste/new.html.twig', [
            'artiste' => $artiste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artiste_show', methods: ['GET'])]
    public function show(Artiste $artiste): Response
    {
        return $this->render('artiste/show.html.twig', [
            'artiste' => $artiste,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artiste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artiste $artiste, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artiste_delete', methods: ['POST'])]
    public function delete(Request $request, Artiste $artiste, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artiste->getId(), $request->request->get('_token'))) {
            $entityManager->remove($artiste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/toggle-favorite', name: 'artiste_toggle_favorite', methods: ['POST'])]
    public function toggleFavorite(Artiste $artiste, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false], 403);
        }

        if ($user->getFavoriteArtistes()->contains($artiste)) {
            $user->removeFavoriteArtiste($artiste);
            $favorite = false;
        } else {
            $user->addFavoriteArtiste($artiste);
            $favorite = true;
        }
        $em->flush();

        return new JsonResponse(['success' => true, 'favorite' => $favorite]);
    }


}

