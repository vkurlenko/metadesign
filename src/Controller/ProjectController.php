<?php

namespace App\Controller;

use App\Entity\Project;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * @var FileService
     */
    private FileService $fileService;

    /**
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @param string $type
     * @param string $identifier
     * @return Project|null
     */
    public function getProject(string $type, string $identifier): ?Project
    {
        $project = null;
        $projectData = $this->fileService->getProjectByIdentifier($type, $identifier);

        if ($projectData) {
            $project = new Project();
            $project->setIdentifier($projectData['identifier']);
            $project->setName($projectData['name']);
            $project->setCity($projectData['city']);
            $project->setText($projectData['text']);
            $project->setDescription($projectData['description']);
            $project->setFiles($projectData['files']);
            $project->setItems($projectData['items']);
            $project->setDir($projectData['dir']);
            $project->setUrl($projectData['url']);
            $project->setTitle($projectData['title']);
        }

        return $project;
    }
}