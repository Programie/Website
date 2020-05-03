<?php
namespace com\selfcoders\website\controller;

use com\selfcoders\website\model\Projects;

abstract class AbstractController
{
    protected Projects $projects;

    public function __construct(Projects $projects)
    {
        $this->projects = $projects;
    }
}