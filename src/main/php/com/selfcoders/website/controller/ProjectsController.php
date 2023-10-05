<?php
namespace com\selfcoders\website\controller;

use com\selfcoders\website\exception\ForbiddenException;
use com\selfcoders\website\exception\NotFoundException;
use com\selfcoders\website\model\Projects;
use com\selfcoders\website\TwigRenderer;
use Twig\Error\Error as TwigError;

class ProjectsController extends AbstractController
{
    /**
     * @return string
     * @throws TwigError
     */
    public function listProjects(): string
    {
        return TwigRenderer::render("projects");
    }

    public function listProjectsOfCategory($params): string
    {
        return TwigRenderer::render("projects-of-category", [
            "title" => Projects::getCategories()[$params["category"]][0],
            "description" => Projects::getCategories()[$params["category"]][1],
            "category" => $params["category"]
        ]);
    }

    /**
     * @param $params
     */
    public function redirectToRepoReadme($params): void
    {
        $project = $this->projects->byName($params["name"]);

        if ($project === null) {
            throw new NotFoundException;
        }

        header(sprintf("Location: %s#readme", $project->getRepoUrl()));
    }

    public function getCoverImage($params): void
    {
        $project = $this->projects->byName($params["name"]);

        if ($project === null) {
            throw new NotFoundException;
        }

        if (!$project->hasCoverImage()) {
            throw new NotFoundException;
        }

        $path = $project->getCoverImagePath();

        header("Content-Type: image/jpeg");
        readfile($path);
    }

    public function update(): string
    {
        $headers = getallheaders();
        if ($headers === false) {
            throw new ForbiddenException;
        }

        if (!isset($headers["X-Update-Token"])) {
            throw new ForbiddenException;
        }

        $requiredToken = getenv("PROJECT_UPDATE_TOKEN");
        if (!$requiredToken) {
            // $requiredToken is null, false, empty string, ...
            throw new ForbiddenException;
        }

        if ($headers["X-Update-Token"] !== $requiredToken) {
            throw new ForbiddenException;
        }

        $repository = $_POST["repository"] ?? null;
        if (!$repository) {
            throw new ForbiddenException;
        }

        $repository = explode("/", $repository)[1];

        $project = $this->projects->byRepository($repository);
        if ($project === null) {
            throw new NotFoundException;
        }

        $project->updateDownloads();
        $this->projects->saveSerialized();

        return sprintf("Updated project %s with %d downloads", $project->name, count($project->downloads));
    }
}