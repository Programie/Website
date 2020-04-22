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

        return $project;
    }
}