<?php

namespace App\Controller;

use App\Service\ProjectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    /**
     * @var array|string[]
     */
    public array $vars = [
        'PHONE'          => '79993475090',
        'PHONE_FORMATED' => '+7 999 347-50-90',
    ];

    private ProjectController $projectController;

    public function __construct(ProjectService $projectService)
    {
        $this->projectController = new ProjectController($projectService);
    }

    #[Route('/')]
    public function index(): Response
    {
        // Здесь лежит массив карточек для галереи в блоке "Портфолио".
        $data = file_get_contents('portfolio_tabs.json');
        $cards = json_decode($data, true);

        $vars = $this->vars;

        $this->vars = array_merge($this->vars, ['CARDS' => $cards['items']]);

        return $this->render('index.html.twig', $this->vars);
    }

    #[Route('/portfolio')]
    public function portfolio(): Response
    {
        return $this->render('portfolio.html.twig', $this->vars);
    }

    #[Route('/about')]
    public function about(): Response
    {
        $images = [
            ['src' => '/img/about/IMG_1.jpg'],
            ['src' => '/img/about/IMG_2.jpg'],
            ['src' => '/img/about/IMG_3.jpg'],
            ['src' => '/img/about/IMG_4.jpg'],
            ['src' => '/img/about/IMG_5.jpg'],
            ['src' => '/img/about/IMG_6.jpg'],
            ['src' => '/img/about/IMG_7.jpg'],
            ['src' => '/img/about/IMG_8.jpg'],
            ['src' => '/img/about/IMG_9.jpg'],
        ];

        $this->vars = array_merge($this->vars, ['IMAGES' => $images]);

        return $this->render('about.html.twig', $this->vars);
    }

    #[Route('/project')]
    public function project(string $identifier): Response
    {
        $project = $this->projectController->getProject($identifier);

        $data = [
            'identifier'  => $project->getIdentifier(),
            'name'        => $project->getName(),
            'description' => $project->getDescription(),
            'files'       => $project->getFiles()
        ];

        $this->vars = array_merge($this->vars, ['ID' => $identifier, 'PROJECT' => $data]);

        return $this->render('project.html.twig', $this->vars);
    }
}
