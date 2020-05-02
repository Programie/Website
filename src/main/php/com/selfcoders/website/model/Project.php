<?php
namespace com\selfcoders\website\model;

use com\selfcoders\website\CustomizedParsedown;
use com\selfcoders\website\Utils;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Symfony\Component\Filesystem\Filesystem;

class Project
{
    public $name;
    public $title;
    public $type;
    public $lastUpdate;
    public $coverImage;
    public $description;
    public $repoName;
    public $gitlabId;
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
        $project->coverImage = $data["coverImage"] ?? "cover-image.jpg";
        $project->description = $data["description"];
        $project->repoName = $data["repoName"];
        $project->gitlabCIUseArtifacts = $data["gitlabCI"]["useArtifacts"] ?? false;
        $project->gitlabCIArtifactPath = $data["gitlabCI"]["artifactPath"] ?? null;
        $project->gitlabCIJob = $data["gitlabCI"]["job"] ?? null;
        $project->downloads = $data["downloads"] ?? [];

        return $project;
    }

    public function getBaseUrl()
    {
        return sprintf("/projects/%s", $this->name);
    }

    public function getCoverImage()
    {
        if ($this->coverImage[0] === "/" or parse_url($this->coverImage, PHP_URL_HOST) !== null) {
            return $this->coverImage;
        } else {
            return sprintf("%s/%s", $this->getBaseUrl(), $this->coverImage);
        }
    }

    public function getResourcePath(string $resource)
    {
        $basePath = sprintf("%s/projects/%s", RESOURCES_ROOT, $this->name);
        $fullPath = sprintf("%s/%s", $basePath, $resource);
        if (Utils::isSafePath($basePath, $fullPath) and is_file($fullPath)) {
            return $fullPath;
        }

        $basePath = sprintf("%s/projects/_default_/%s", RESOURCES_ROOT, $this->type);
        $fullPath = sprintf("%s/%s", $basePath, $resource);
        if (Utils::isSafePath($basePath, $fullPath) and is_file($fullPath)) {
            return $fullPath;
        }

        return null;
    }

    public function getContent()
    {
        $filename = sprintf("%s/projects-html/%s/index.html", CACHE_ROOT, $this->name);
        if (USE_CACHE and file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            return $this->getContentFromMarkdown();
        }
    }

    public function fetchGitlabArtifacts()
    {
        $gitlabClient = new Client([
            "base_uri" => "https://gitlab.com/api/v4/"
        ]);

        $tags = json_decode($gitlabClient->get(sprintf("projects/%d/repository/tags", $this->gitlabId))->getBody()->getContents(), true);

        $this->downloads = [];

        foreach ($tags as $tag) {
            $tagName = $tag["name"];
            $date = $tag["commit"]["created_at"];

            $artifactPath = $this->gitlabCIArtifactPath;
            if ($artifactPath === null) {
                $artifactPath = "download";
            } else {
                $artifactPath = sprintf("raw/%s", $artifactPath);
            }

            $this->downloads[] = [
                "url" => sprintf("https://gitlab.com/Programie/%s/-/jobs/artifacts/%s/%s?job=%s", $this->repoName, $tagName, $artifactPath, $this->gitlabCIJob),
                "date" => $date,
                "title" => $tagName
            ];
        }
    }

    public function getContentFromMarkdown()
    {
        $parsedown = new CustomizedParsedown;

        $parsedown->baseUrl = $this->getBaseUrl();

        $html = $parsedown->text(file_get_contents($this->getResourcePath("index.md")));

        if (USE_CACHE) {
            $filesystem = new Filesystem;
            $filesystem->dumpFile(sprintf("%s/projects-html/%s/index.html", CACHE_ROOT, $this->name), $html);
        }

        return $html;
    }
}