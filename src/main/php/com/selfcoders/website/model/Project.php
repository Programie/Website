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
    public $gitlabCIUseArtifacts;
    public $gitlabCIArtifactPath;
    public $gitlabCIJob;
    public $downloads = [];

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
        $project->gitlabCIUseArtifacts = $data["gitlabCI"]["useArtifacts"] ?? false;
        $project->gitlabCIArtifactPath = $data["gitlabCI"]["artifactPath"] ?? null;
        $project->gitlabCIJob = $data["gitlabCI"]["job"] ?? null;
        $project->downloads = $data["downloads"] ?? [];

        return $project;
    }

    public function content()
    {
        $filename = sprintf("%s/projects-html/%s/index.html", CACHE_ROOT, $this->name);
        if (!file_exists($filename)) {
            return null;
        }

        return file_get_contents($filename);
    }
}