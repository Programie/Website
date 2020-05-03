<?php
namespace com\selfcoders\website\model;

use ArrayObject;
use com\selfcoders\website\GitlabAPI;
use Exception;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Projects extends ArrayObject
{
    /**
     * @return string[][]
     */
    public static function getTypes()
    {
        return [
            ["application", "Applications & Tools"],
            ["minecraft-plugin", "Minecraft plugins"],
            ["php-library", "PHP libraries"]
        ];
    }

    /**
     * @return Projects
     * @throws Exception
     */
    public static function load(): self
    {
        $projects = new self;

        foreach (Yaml::parseFile(RESOURCES_ROOT . "/projects.yml") as $projectData) {
            $project = Project::fromArray($projectData);
            $projects->append($project);
        }

        $projects->uasort(function (Project $project1, Project $project2) {
            return strcmp($project1->title, $project2->title);
        });

        return $projects;
    }

    /**
     * @return Projects
     * @throws Exception
     */
    public static function loadSerialized(): self
    {
        $filename = CACHE_ROOT . "/projects.serialized";
        if (!file_exists($filename)) {
            return self::load();
        }

        $data = unserialize(file_get_contents($filename));
        if ($data instanceof self) {
            return $data;
        }

        throw new RuntimeException(sprintf("Data is not an instance of %s", self::class));
    }

    public function saveSerialized(): void
    {
        $filesystem = new Filesystem;

        $filesystem->dumpFile(CACHE_ROOT . "/projects.serialized", serialize($this));
    }

    public function fetchGitLabIds(): void
    {
        $gitlabAPI = new GitlabAPI;

        $gitlabProjects = $gitlabAPI->getProjectsOfUser("Programie");

        $repoIds = [];

        foreach ($gitlabProjects as $gitlabProject) {
            $repoName = $gitlabProject["path"];

            $repoIds[$repoName] = $gitlabProject["id"];
        }

        /**
         * @var $project Project
         */
        foreach ($this as $project) {
            $project->gitlabId = $repoIds[$project->repoName] ?? null;
        }
    }

    /**
     * @param string $type
     * @return Projects
     */
    public function ofType(string $type): self
    {
        $projects = new self;

        /**
         * @var $project Project
         */
        foreach ($this as $project) {
            if ($project->type === $type) {
                $projects->append($project);
            }
        }

        return $projects;
    }

    /**
     * @param string $name
     * @return Project|null
     */
    public function byName(string $name): ?Project
    {
        /**
         * @var $project Project
         */
        foreach ($this as $project) {
            if ($project->name === $name) {
                return $project;
            }
        }

        return null;
    }

    public function latest(int $limit): self
    {
        $projects = clone $this;

        $projects->uasort(function (Project $project1, Project $project2) {
            if ($project1->lastUpdate < $project2->lastUpdate) {
                return 1;
            } elseif ($project1->lastUpdate > $project2->lastUpdate) {
                return -1;
            } else {
                return ($project1->title > $project2->title);
            }
        });

        return $projects->slice(0, $limit);
    }

    public function slice(int $offset, int $length): self
    {
        return new self(array_slice($this->getArrayCopy(), $offset, $length));
    }
}