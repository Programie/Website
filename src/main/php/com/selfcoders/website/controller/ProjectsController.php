<?php
namespace com\selfcoders\website\controller;

use com\selfcoders\website\exception\NotFoundException;
use com\selfcoders\website\TwigRenderer;

class ProjectsController extends AbstractController
{
    public function listProjects()
    {
        return TwigRenderer::render("projects");
    }

    public function showProject($params)
    {
        $project = $this->projects->byName($params["name"]);

        if ($project === null) {
            throw new NotFoundException;
        }

        return TwigRenderer::render("project", [
            "project" => $project
        ]);
    }

    public function getResource($params)
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
}