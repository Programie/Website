<?php
namespace com\selfcoders\website\model;

use ArrayObject;
use Exception;
use Symfony\Component\Yaml\Yaml;

class Projects extends ArrayObject
{
    /**
     * @return Projects
     * @throws Exception
     */
    public static function load()
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
     * @param string $type
     * @return Projects
     */
    public function ofType(string $type)
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

    public function latest(int $limit)
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

    public function slice(int $offset, int $length)
    {
        return new self(array_slice($this->getArrayCopy(), $offset, $length));
    }
}