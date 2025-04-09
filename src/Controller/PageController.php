<?php

namespace App\Controller;

use App\Service\FileService;
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

    /**
     * @var ProjectController
     */
    private ProjectController $projectController;

    public function __construct()
    {
        $fileService = new FileService();
        $this->projectController = new ProjectController($fileService);
    }

    /**
     * @return Response
     */
    #[Route('/')]
    public function index(): Response
    {
        $data = $this->getDataFromJson('portfolio.json');
        shuffle($data['items']);

        $services = $this->getDataFromJson('services.json');

        $this->vars = array_merge(
            $this->vars,
            ['CURRENT_PAGE' => 'index'],
            ['CARDS' => $data['items']],
            ['SERVICES' => $services['items']]
        );

        return $this->render('index.html.twig', $this->vars);
    }

    /**
     * @return Response
     */
    #[Route('/portfolio')]
    public function portfolio(): Response
    {
        $data = $this->getDataFromJson('portfolio.json');
        shuffle($data['items']);

        $this->vars = array_merge(
            $this->vars,
            ['CURRENT_PAGE' => 'portfolio'],
            ['CARDS' => $data['items']]
        );

        return $this->render('portfolio.html.twig', $this->vars);
    }

    /**
     * @return Response
     */
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

        $this->vars = array_merge(
            $this->vars,
            ['CURRENT_PAGE' => 'about'],
            ['IMAGES' => $images]
        );

        return $this->render('about.html.twig', $this->vars);
    }

    /**
     * @param string $identifier
     * @return Response
     */
    #[Route('/project')]
    public function project(string $identifier): Response
    {
        $project = $this->projectController->getProject(FileService::TYPE_PROJECTS, $identifier);

        $data = [
            'identifier'  => $project->getIdentifier(),
            'name'        => $project->getName(),
            'city'        => $project->getCity(),
            'text'      => $project->getText(),
            'description' => $project->getDescription(),
            'files'       => $project->getFiles()
        ];

        $this->vars = array_merge(
            $this->vars,
            ['CURRENT_PAGE' => 'project'],
            [
                'ID'      => $identifier,
                'PROJECT' => $data
            ]
        );

        return $this->render('project.html.twig', $this->vars);
    }

    /**
     * @param string $identifier
     * @return Response
     */
    #[Route('/drawing')]
    public function drawing(string $identifier): Response
    {
        $project = $this->projectController->getProject(FileService::TYPE_DRAWINGS, $identifier);

        $data = [
            'identifier'  => $project->getIdentifier(),
            'name'        => $project->getName(),
            'description' => $project->getDescription(),
            'files'       => $project->getFiles()
        ];

        $this->vars = array_merge(
            $this->vars,
            ['CURRENT_PAGE' => 'drawing'],
            [
                'ID'      => $identifier,
                'DRAWING' => $data
            ]
        );

        return $this->render('drawing.html.twig', $this->vars);
    }

    /**
     * @param string $identifier
     * @return Response
     */
    #[Route('/service')]
    public function service(string $identifier): Response
    {
        $service = $this->projectController->getProject(FileService::TYPE_SERVICES, $identifier);

        $this->vars = array_merge(
            $this->vars,
            [ 'CURRENT_PAGE' => 'service'],
            [
                'ID'           => $identifier,
                'SERVICE'      => $service
            ]
        );

        return $this->render('service.html.twig', $this->vars);
    }

    /**
     * @param string $filename
     * @return array
     */
    private function getDataFromJson(string $filename): array
    {
        $items = [];

        $data = file_get_contents($filename);

        if ($data) {
            $items = json_decode($data, true);
        }

        return is_array($items) ? $items : [];
    }
}
