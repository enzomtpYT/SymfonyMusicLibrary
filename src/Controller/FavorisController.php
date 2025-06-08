<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    #[Route('/favoris', name: 'app_favoris')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            // Redirige vers la page de login si non connecté
            return $this->redirectToRoute('app_login');
        }

        return $this->render('favoris/favoris.html.twig', [
            'favorite_artists' => $user->getFavoriteArtistes(),
            'favorite_albums' => $user->getFavoriteReleases(),
            'favorite_tracks' => $user->getFavoriteTracks(),
        ]);
    }
}