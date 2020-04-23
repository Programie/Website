<?php
namespace com\selfcoders\website\model;

use DateTime;
use Exception;

class Project
{
    public $name;
    public $title;
    public $type;
    public $lastUpdate;
    public $coverImage;
    public $description;
    public $repoName;

    /**
     * @param array $data
     * @return Project
     * @throws Exception
     */
    public static function fromArray(array $data)
    {
        $project = new self;

        $project->name = $data["name"];
        $project->title = $data["title"];
        $project->type = $data["type"];
        $project->lastUpdate = new DateTime($data["lastUpdate"]);
        $project->coverImage = $data["coverImage"];
        $project->description = $data["description"];
        $project->repoName = $data["repoName"];

        return $project;
    }

    public function content()
    {
        $filename = sprintf("%s/projects/%s.html", RESOURCES_ROOT, $this->name);
        if (!file_exists($filename)) {
            return null;
        }

        return file_get_contents($filename);
    }
}