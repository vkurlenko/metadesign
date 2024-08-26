<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
        ]);
    }

    #[Route('/about')]
    public function about(): Response
    {
        return $this->render('about.html.twig', [
        ]);
    }

    #[Route('/contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig', [
        ]);
    }

    #[Route('/service')]
    public function service(): Response
    {
        return $this->render('service.html.twig', [
        ]);
    }

    #[Route('/portfolio')]
    public function portfolio(): Response
    {
        return $this->render('portfolio.html.twig', [
        ]);
    }
}
