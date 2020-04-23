#! /usr/bin/env php
<?php
use com\selfcoders\website\CustomizedParsedown;
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;

require_once __DIR__ . "/../bootstrap.php";

$projects = Projects::load();

$parsedown = new CustomizedParsedown;

/**
 * @var $project Project
 */
foreach ($projects as $project) {
    $filename = RESOURCES_ROOT . "/projects/" . $project->name;
    if (!file_exists($filename . ".md")) {
        continue;
    }

    $parsedown->baseUrl = sprintf("https://gitlab.com/Programie/%s/-/blob/master/", $project->repoName);
    $parsedown->imageBaseUrl = sprintf("https://gitlab.com/Programie/%s/-/raw/master/", $project->repoName);

    file_put_contents($filename . ".html", $parsedown->text(file_get_contents($filename . ".md")));
}