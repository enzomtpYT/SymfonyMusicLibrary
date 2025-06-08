<?php

namespace App\Controller;

use App\Entity\Release;
use App\Form\ReleaseType;
use App\Repository\ReleaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/release')]
class ReleaseController extends AbstractController
{
    #[Route('/', name: 'app_release_index', methods: ['GET'])]
    public function index(ReleaseRepository $releaseRepository): Response
    {
        return $this->render('release/index.html.twig', [
            'releases' => $releaseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_release_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $release = new Release();
        $form = $this->createForm(ReleaseType::class, $release);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($release);
            $entityManager->flush();

            return $this->redirectToRoute('app_release_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('release/new.html.twig', [
            'release' => $release,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_release_show', methods: ['GET'])]
    public function show(Release $release): Response
    {
        return $this->render('release/show.html.twig', [
            'release' => $release,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_release_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Release $release, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReleaseType::class, $release);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_release_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('release/edit.html.twig', [
            'release' => $release,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_release_delete', methods: ['POST'])]
    public function delete(Request $request, Release $release, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$release->getId(), $request->request->get('_token'))) {
            $entityManager->remove($release);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_release_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/toggle-favorite', name: 'release_toggle_favorite', methods: ['POST'])]
    public function toggleFavorite(Release $release, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false], 403);
        }

        if ($user->getFavoriteReleases()->contains($release)) {
            $user->removeFavoriteRelease($release);
            $favorite = false;
        } else {
            $user->addFavoriteRelease($release);
            $favorite = true;
        }
        $em->flush();

        return new JsonResponse(['success' => true, 'favorite' => $favorite]);
    }


}
