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
            "category" => $params["category"]
        ]);
    }

    /**
     * @param $params
     * @return string
     * @throws TwigError
     */
    public function showProject($params): string
    {
        $project = $this->projects->byName($params["name"]);

        if ($project === null) {
            throw new NotFoundException;
        }

        return TwigRenderer::render("project", [
            "project" => $project
        ]);
    }

    public function getResource($params): void
    {
        $project = $this->projects->byName($params["name"]);

        if ($project === null) {
            throw new NotFoundException;
        }

        $filename = $project->getResourcePath($params["resource"]);
        if ($filename === null) {
            throw new NotFoundException;
        }

        $contentType = mime_content_type($filename);
        if ($contentType !== false) {
            header(sprintf("Content-Type: %s", $contentType));
        }

        readfile($filename);
    }

    public function update($params): string
    {
        $headers = getallheaders();
        if ($headers === false) {
            throw new ForbiddenException;
        }

        if (!isset($headers["X-Gitlab-Token"])) {
            throw new ForbiddenException;
        }

        $requiredToken = getenv("GITLAB_TOKEN");
        if (!$requiredToken) {
            // $requiredToken is null, false, empty string, ...
            throw new ForbiddenException;
        }

        if ($headers["X-Gitlab-Token"] !== $requiredToken) {
            throw new ForbiddenException;
        }

        $project = $this->projects->byName($params["name"]);

        if ($project === null) {
            throw new NotFoundException;
        }

        if ($project->gitlabId !== null) {
            $project->updateDownloadsFromGitlab();
            $this->projects->saveSerialized();

            return sprintf("Updated project %s with %d downloads", $project->name, count($project->downloads));
        }

        return "Nothing to update";
    }
}