<?php
namespace com\selfcoders\website\model;

use com\selfcoders\website\GitHubAPI;
use DateTime;
use Exception;
use Parsedown;

class Project
{
    public string $name;
    public string $title;
    public string $category;
    public DateTime $startDate;
    public ?DateTime $forceLastUpdate = null;
    public Release $lastRelease;
    public string $description;
    public string $repoName;
    public bool $useSourceAsDownload;
    public string $sourceBranch;
    public array $sites = [];
    public ?Downloads $downloads = null;

    public function __construct()
    {
        $this->lastRelease = new Release;
    }

    /**
     * @param array $data
     * @return Project
     * @throws Exception
     */
    public static function fromArray(array $data): Project
    {
        $project = new self;

        $project->name = $data["name"];
        $project->title = $data["title"];
        $project->category = $data["category"];
        $project->startDate = new DateTime($data["startDate"]);
        $project->description = $data["description"];
        $project->repoName = $data["repoName"];
        $project->useSourceAsDownload = $data["useSourceAsDownload"] ?? false;
        $project->sourceBranch = $data["sourceBranch"] ?? "master";

        $project->sites[] = Site::fromTypeValue("github", sprintf("Programie/%s", $project->repoName));

        foreach ($data["sites"] ?? [] as $siteType => $siteValue) {
            $project->sites[] = Site::fromTypeValue($siteType, $siteValue);
        }

        $forceLastUpdate = $data["forceLastUpdate"] ?? null;
        if ($forceLastUpdate !== null) {
            $project->forceLastUpdate = new DateTime($forceLastUpdate);
        }

        return $project;
    }

    public function getBaseUrl(): string
    {
        return sprintf("/projects/%s", $this->name);
    }

    public function getCoverImagePath()
    {
        return sprintf("%s/assets/images/projects/%s.jpg", RESOURCES_ROOT, $this->name);
    }

    public function hasCoverImage()
    {
        return file_exists($this->getCoverImagePath());
    }

    public function getCoverImage()
    {
        return sprintf("%s/cover-image.jpg", $this->getBaseUrl());
    }

    public function getRepoUrl(): string
    {
        return sprintf("https://github.com/Programie/%s", $this->repoName);
    }

    public function updateData(): void
    {
        $gitHubApi = new GitHubAPI;

        if ($this->useSourceAsDownload) {
            $this->setLastUpdate($gitHubApi->getLastUpdate($this->repoName, $this->sourceBranch));
            $this->lastRelease->name = $this->sourceBranch;

            $this->downloads = new Downloads;
            $this->downloads->append(new Download($gitHubApi->getSourceDownloadUrl($this->repoName, $this->sourceBranch), $this->sourceBranch));
            return;
        }

        $release = $gitHubApi->getLatestReleaseForRepository($this->repoName);

        if ($release !== null) {
            try {
                $date = new DateTime($release["published_at"]);
            } catch (Exception $exception) {
                $date = new DateTime;
            }

            $this->setLastUpdate($date);
            $this->lastRelease->name = $release["name"];

            $parsedown = new Parsedown;
            $this->lastRelease->notes = $parsedown->text($release["body"] ?? "");

            $this->downloads = new Downloads;

            $assets = $release["assets"] ?? [];
            if (empty($assets)) {
                $this->downloads->append(new Download($release["zipball_url"], "zip"));
            } else {
                foreach ($assets as $asset) {
                    $this->downloads->append(new Download($asset["browser_download_url"], $asset["name"]));
                }
            }
        }
    }

    private function setLastUpdate(DateTime $dateTime): void
    {
        if ($this->forceLastUpdate !== null) {
            $this->lastRelease->date = $this->forceLastUpdate;
            return;
        }

        $this->lastRelease->date = $dateTime;
    }
}