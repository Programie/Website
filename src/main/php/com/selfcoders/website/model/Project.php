<?php
namespace com\selfcoders\website\model;

use com\selfcoders\website\CustomizedParsedown;
use com\selfcoders\website\GitlabAPI;
use com\selfcoders\website\Utils;
use DateTime;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

class Project
{
    public string $name;
    public string $title;
    public string $type;
    public ?DateTime $lastUpdate;
    public string $coverImage;
    public string $description;
    public string $repoName;
    public ?int $gitlabId;
    public bool $useSourceAsDownload;
    public string $sourceBranch;
    public Downloads $downloads;

    public function __construct()
    {
        $this->downloads = new Downloads;
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
        $project->type = $data["type"];
        $project->coverImage = $data["coverImage"] ?? "cover-image.jpg";
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

    public function getCoverImage()
    {
        if ($this->coverImage[0] === "/" or parse_url($this->coverImage, PHP_URL_HOST) !== null) {
            return $this->coverImage;
        } else {
            return sprintf("%s/%s", $this->getBaseUrl(), $this->coverImage);
        }
    }

    public function getResourcePath(string $resource): ?string
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

    public function getContent(): string
    {
        $filename = sprintf("%s/projects-html/%s/index.html", CACHE_ROOT, $this->name);
        if (USE_CACHE and file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            return $this->getContentFromMarkdown();
        }
    }

    public function updateDownloadsFromGitlab(): void
    {
        $gitlabAPI = new GitlabAPI;

        $releases = $gitlabAPI->getReleasesForProject($this->gitlabId);

        $latestDate = null;
        $this->downloads = new Downloads;

        foreach ($releases as $release) {
            try {
                $date = new DateTime($release["released_at"]);
            } catch (Exception $exception) {
                $date = new DateTime;
            }

            $latestDate = max($latestDate, $date);

            $assetLinks = $release["assets"]["links"] ?? null;

            if (empty($assetLinks)) {
                $downloadUrl = $gitlabAPI->getSourceDownloadUrl("Programie", $this->repoName, $release["tag_name"]);
            } else {
                $downloadUrl = $assetLinks[0]["url"];
            }

            $this->downloads->append(new Download($downloadUrl, $date, $release["name"]));
        }

        if ($this->useSourceAsDownload) {
            $branches = $gitlabAPI->getBranchesOfProject($this->gitlabId);

            $date = new DateTime;

            foreach ($branches as $branch) {
                if ($branch["name"] === $this->sourceBranch) {
                    try {
                        $date = new DateTime($branch["commit"]["committed_date"]);
                    } catch (Exception $exception) {
                        $date = new DateTime;
                    }
                    break;
                }
            }

            $latestDate = max($latestDate, $date);

            $this->downloads->append(new Download($gitlabAPI->getSourceDownloadUrl("Programie", $this->repoName, $this->sourceBranch), $date, $this->sourceBranch));
        }

        $this->lastUpdate = $latestDate;
    }

    public function getContentFromMarkdown(): string
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