<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ConferenceController extends AbstractController
{

    public function __construct
    (
        private ConferenceRepository $conferenceRepository,
        private CommentRepository $commentRepository
    ) {}

    #[Route(path: '/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('conference/index.html.twig', [
            'conferences' => $this->conferenceRepository->findAll()
        ]);
    }

    #[Route(path: '/conference/{id}', name: 'conference_show')]
    public function show(int $id, Request $request) : Response
    {


        $conference = $this->conferenceRepository->find($id);

        $pageNumber = max(1, $request->query->getInt('pageNumber', 1));

        $paginator = $this->commentRepository->getCommentPaginator(
            $conference, $pageNumber
        );


        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'nextPage' => $pageNumber + 1,
            'prevPage' => $pageNumber < 2 ? null : $pageNumber - 1,
            'currentPage' => $pageNumber
        ]);
    }
}
