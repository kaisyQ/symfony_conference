<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class ConferenceController extends AbstractController
{

    public function __construct
    (
        private ConferenceRepository $conferenceRepository,
        private CommentRepository $commentRepository,
        private EntityManagerInterface $em,
        private MessageBusInterface $messageBus
    ) {}

    #[Route(path: '/', name: 'homepage')]
    public function index(): Response
    {

        return $this->render('conference/index.html.twig', [
            'conferences' => $this->conferenceRepository->findAll()
        ]);
    }

    #[Route(path: '/conference/{slug}', name: 'conference_show')]
    public function show(string $slug, Request $request, #[Autowire('%photo_dir%')] string $photoDir) : Response
    {

        $comment = new Comment();
        
        $form = $this->createForm(CommentType::class, $comment);


        $form->handleRequest($request);


        $conference = $this->conferenceRepository->findOneBy(['slug' => $slug]);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($conference);
            
            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                $photo->move($photoDir, $filename);
                $comment->setPhotoFilename($filename);
            }

            $this->em->persist($comment);
            $this->em->flush();
            $this->messageBus->dispatch(new CommentMessage($comment->getId()));

        }

        $pageNumber = max(1, $request->query->getInt('pageNumber', 1));

        $paginator = $this->commentRepository->getCommentPaginator(
            $conference, $pageNumber
        );



        //dd($paginator);
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'nextPage' => $pageNumber + 1,
            'prevPage' => $pageNumber < 2 ? null : $pageNumber - 1,
            'currentPage' => $pageNumber,
            'photoDir' => $photoDir,
            "commentForm" => $form
        ]);
    }
}
