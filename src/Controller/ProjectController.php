<?php

namespace App\Controller;

use App\Entity\Project;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * @var ProjectService
     */
    private ProjectService $projectService;

    /**
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * @param string $identifier
     * @return Project|null
     */
    public function getProject(string $identifier): ?Project
    {
        $project = null;
        $projectData = $this->projectService->getProjectByIdentifier($identifier);

        if ($projectData) {
            $project = new Project();
            $project->setIdentifier($projectData['identifier']);
            $project->setName($projectData['name']);
            $project->setDescription($projectData['description']);
            $project->setFiles($projectData['files']);
        }

        return $project;
    }
}