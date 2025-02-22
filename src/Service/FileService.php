<?php

namespace App\Service;

class FileService
{
    const TYPE_PROJECTS = 'projects';
    const TYPE_DRAWINGS = 'drawings';
    const TYPE_SERVICES = 'services';

    /**
     * @param string $type
     * @param string $identifier
     * @return array
     */
    public function getProjectByIdentifier(string $type, string $identifier): array
    {
        $project = [];
        $data = $this->getData($type);

        $projects = array_filter($data['items'], function ($item) use ($identifier) {
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
     * @param string $type
     * @return array
     */
    private function getData(string $type): array
    {
        $filename = null;
        $projects = [];

        switch ($type) {
            case self::TYPE_PROJECTS:
                $filename = 'projects.json';
                break;
            case self::TYPE_DRAWINGS:
                $filename = 'drawings.json';
                break;
            case self::TYPE_SERVICES:
                $filename = 'services.json';
                break;
            default:
                break;
        }

        if ($filename) {
            $data = file_get_contents($filename);
            $projects = json_decode($data, true);
        }

        return $projects;
    }

    /**
     * Считывает все файлы из директории проекта $project['dir']
     *
     * @param array $project
     * @return array|string[]
     */
    private function getFiles(array $project): array
    {
        $dir = $project['dir'];
        $files = [];

        if (is_dir(getcwd() . $dir)) {
            $fileNames = scandir(getcwd() . $dir);

            $fileNames = array_filter($fileNames, function ($fileName) {
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                return ! in_array($fileName, ['.', '..']) && $ext === 'jpg';
            });

            $files = array_map(function ($fileName) use ($dir) {
                return $dir . '/' . $fileName;
            }, $fileNames);
        }

        return $files;
    }
}