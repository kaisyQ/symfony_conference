<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ConferenceController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function index(Request $request): Response
    {
        $name = $request->query->get('name');
        return $this->json([
            'name' => $name
        ]);
    }
}
