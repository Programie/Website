<?php
namespace com\selfcoders\website\model;

use com\selfcoders\website\CustomizedParsedown;
use com\selfcoders\website\GitHubAPI;
use com\selfcoders\website\Utils;
use DateTime;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

class Project
{
    public string $name;
    public string $title;
    public string $category;
    public ?string $extName;
    public DateTime $startDate;
    public ?DateTime $lastUpdate = null;
    public ?string $lastRelease = null;
    public string $description;
    public string $repoName;
    public bool $useSourceAsDownload;
    public string $sourceBranch;
    public ?Downloads $downloads = null;

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
        $project->extName = $data["extName"] ?? null;
        $project->startDate = new DateTime($data["startDate"]);
        $project->description = $data["description"];
        $project->repoName = $data["repoName"];
        $project->useSourceAsDownload = $data["useSourceAsDownload"] ?? false;
        $project->sourceBranch = $data["sourceBranch"] ?? "master";

        return $project;
    }

    public function getBaseUrl(): string
    {
        return sprintf("/projects/%s", $this->name);
    }

    public function hasCoverImage()
    {
        return file_exists(sprintf("%s/projects/%s/cover-image.jpg", RESOURCES_ROOT, $this->name));
    }

    public function getCoverImage()
    {
        return sprintf("%s/cover-image.jpg", $this->getBaseUrl());
    }

    public function getRepoUrl(): string
    {
        return sprintf("https://github.com/Programie/%s", $this->repoName);
    }

    public function getResourcePath(string $resource): ?string
    {
        $basePath = sprintf("%s/projects/%s", RESOURCES_ROOT, $this->name);
        $fullPath = sprintf("%s/%s", $basePath, $resource);
        if (Utils::isSafePath($basePath, $fullPath) and is_file($fullPath)) {
            return $fullPath;
        }

        $basePath = sprintf("%s/projects/_default_/%s", RESOURCES_ROOT, $this->category);
        $fullPath = sprintf("%s/%s", $basePath, $resource);
        if (Utils::isSafePath($basePath, $fullPath) and is_file($fullPath)) {
            return $fullPath;
        }

        return null;
    }

    public function getContent(): string
    {
        $filename = sprintf("%s/projects-html/%s/index.html", CACHE_ROOT, $this->name);
        if (USE_CACHE and file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            return $this->getContentFromMarkdown();
        }
    }

    public function updateDownloads(): void
    {
        $gitHubApi = new GitHubAPI;

        if ($this->useSourceAsDownload) {
            $this->lastUpdate = $gitHubApi->getLastUpdate($this->repoName, $this->sourceBranch);
            $this->lastRelease = $this->sourceBranch;

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

            $this->lastUpdate = $date;
            $this->lastRelease = $release["name"];

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

    public function getContentFromMarkdown(): string
    {
        $filename = $this->getResourcePath("index.md");

        if (!file_exists($filename)) {
            return "";
        }

        $parsedown = new CustomizedParsedown;

        $parsedown->baseUrl = $this->getBaseUrl();

        $html = $parsedown->text(file_get_contents($filename));

        if (USE_CACHE) {
            $filesystem = new Filesystem;
            $filesystem->dumpFile(sprintf("%s/projects-html/%s/index.html", CACHE_ROOT, $this->name), $html);
        }

        return $html;
    }
}