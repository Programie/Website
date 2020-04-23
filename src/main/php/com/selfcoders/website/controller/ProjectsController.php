<?php
namespace com\selfcoders\website\controller;

use com\selfcoders\website\TwigRenderer;

class ProjectsController
{
    public function listProjects()
    {
        return TwigRenderer::render("projects");
    }

    public function showProject($params)
    {
        return TwigRenderer::render("project", [
            "name" => $params["name"]
        ]);
    }
}