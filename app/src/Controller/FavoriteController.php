<?php

/**
 * Favorite controller.
 */

namespace App\Controller;

use App\Entity\Album;
use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FavoriteController.
 */
class FavoriteController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User Service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(private readonly UserServiceInterface $userService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/favorites', name: 'user_favorites')]
    public function favorites(): Response
    {
        $user = $this->getUser();

        return $this->render('favorite/index.html.twig', [
            'albums' => $user->getFavoriteAlbums(),
        ]);
    }

    /**
     * Toggle favorite action.
     *
     * @param Album                  $album Album entity
     * @param EntityManagerInterface $em    Entity manager
     *
     * @return RedirectResponse Redirect response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/album/{id}/favorite', name: 'album_favorite', methods: ['POST'])]
    public function toggleFavorite(#[MapEntity(id: 'id')] Album $album, EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            throw $this->createAccessDeniedException();
        }

        if ($user->hasFavorited($album)) {
            $user->removeFavoriteAlbum($album);
        } else {
            $user->addFavoriteAlbum($album);
        }

        $this->userService->save($user);

        $this->addFlash(
            'success',
            $this->translator->trans('message.AddedToFavorites')
        );

        return $this->redirectToRoute('album_view', ['id' => $album->getId()]);
    }
}
