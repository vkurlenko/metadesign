<?php

namespace App\Service;

class ProjectService
{
    /**
     * @param $identifier
     * @return array
     */
    public function getProjectByIdentifier($identifier): array
    {
        $project = [];
        $data = $this->getData();

        $projects = array_filter($data['projects'], function ($item) use ($identifier) {
            return $item['identifier'] == $identifier;
        });

        $projects = array_values($projects);

        if (! empty($projects)) {
            $project = $projects[0];

            $project['files'] = $this->getFiles($project);
        }

        return $project;
    }

    /**
     * @return array
     */
    private function getData(): array
    {
        // В projects.json лежит массив проектов.
        $data = file_get_contents('projects.json');
        $projects = json_decode($data, true);

        return $projects;
    }

    /**
     * Считывает все файлы из директории проекта $project['dir']
     *
     * @param $project
     * @return array|string[]
     */
    private function getFiles($project): array
    {
        $dir = $project['dir'];
        $files = [];

        if (is_dir(getcwd() . $dir)) {
            $fileNames = scandir(getcwd() . $dir);

            $fileNames = array_filter($fileNames, function ($fileName) {
                return ! in_array($fileName, ['.', '..']);
            });

            $files = array_map(function ($fileName) use ($dir) {
                return $dir . '/' . $fileName;
            }, $fileNames);
        }

        return $files;
    }
}