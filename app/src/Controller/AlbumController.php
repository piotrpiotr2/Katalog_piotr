<?php

/**
 * Album controller.
 */

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\Type\AlbumType;
use App\Form\Type\CommentType;
use App\Resolver\AlbumListInputFiltersDtoResolver;
use App\Service\CommentServiceInterface;
use App\Service\AlbumServiceInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Dto\AlbumListInputFiltersDto;

/**
 * Class AlbumController.
 */
#[Route('/album')]
class AlbumController extends AbstractController
{
    /**
     * Album Service.
     */
    private AlbumServiceInterface $albumService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Comment Service.
     */
    private CommentServiceInterface $commentService;


    /**
     * Constructor.
     *
     * @param AlbumServiceInterface   $albumService   Album service
     * @param CommentServiceInterface $commentService Comment Service
     * @param TranslatorInterface     $translator     Translator
     */
    public function __construct(AlbumServiceInterface $albumService, CommentServiceInterface $commentService, TranslatorInterface $translator)
    {
        $this->albumService = $albumService;
        $this->commentService = $commentService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param AlbumListInputFiltersDto $filters Input filters
     * @param int                      $page    Page number
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'album_index',
        methods: 'GET'
    )]
    public function index(#[MapQueryString(resolver: AlbumListInputFiltersDtoResolver::class)] AlbumListInputFiltersDto $filters, #[MapQueryParameter] int $page = 1): Response
    {

        $pagination = $this->albumService->getPaginatedList(
            $page,
            $filters
        );

        return $this->render('album/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * View action.
     *
     * @param Album   $album   Album entity
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws NonUniqueResultException
     */
    #[Route(
        '/{id}',
        name: 'album_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function view(Album $album, Request $request): Response
    {

        $comment = new Comment();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
            $comment->setUser($user);
        }

        $comment->setAlbum($album);

        $form = $this->createForm(
            CommentType::class,
            $comment,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('album_view', ['id' => $album->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->commentService->Save($comment);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.comment_created_successfully')
                );

            return $this->redirectToRoute('album_view', ['id' => $album->getId()]);
        }
            $comment = $this->commentService->findBy([$album->getId()]);

        return $this->render(
            'album/view.html.twig',
            [
                'album' => $album,
                'form' => $form->createView(),
                'comment' => $comment,
                'user' => $user,
            ]
        );
    }


    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'album_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->albumService->save($album);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('album_index');
        }

        return $this->render(
            'album/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Album   $album   Album entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/edit',
        name: 'album_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    public function edit(Request $request, Album $album): Response
    {
        $form = $this->createForm(
            AlbumType::class,
            $album,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('album_edit', ['id' => $album->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->albumService->save($album);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('album_index');
        }

        return $this->render(
            'album/edit.html.twig',
            [
                'form' => $form->createView(),
                'album' => $album,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Album   $album   Album entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/delete',
        name: 'album_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    public function delete(Request $request, Album $album): Response
    {
        $form = $this->createForm(FormType::class, $album, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('album_delete', ['id' => $album->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->albumService->delete($album);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('album_index');
        }

        return $this->render(
            'album/delete.html.twig',
            [
                'form' => $form->createView(),
                'album' => $album,
            ]
        );
    }
}
