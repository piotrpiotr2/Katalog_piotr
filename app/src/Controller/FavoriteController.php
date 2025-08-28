<?php

/**
 * Favorite controller.
 */

namespace App\Controller;

use App\Dto\AlbumListInputFiltersDto;
use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
//    #[Route('/favorites', name: 'favorites_list', methods: ['GET'])]
//    public function toggleFavorite(Album $album, EntityManagerInterface $em): RedirectResponse
//    {
//        $pagination = $this->favoriteService->getPaginatedList($page);
//
//        return $this->render('favorite/index.html.twig', ['pagination' => $pagination]);
//    }
    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route('/favorites', name: 'user_favorites')]
    public function favorites(): Response
    {
        $user = $this->getUser();
        return $this->render('favorite/index.html.twig', [
            'albums' => $user->getFavoriteAlbums(),
        ]);
    }
    #[Route('/album/{id}/favorite', name: 'album_favorite', methods: ['POST'])]
    public function toggleFavorite(Album $album, EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        if ($user->hasFavorited($album)) {
            $user->removeFavoriteAlbum($album);
        } else {
            $user->addFavoriteAlbum($album);
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('album_view', ['id' => $album->getId()]);
    }
}