#! /usr/bin/env php
<?php
use com\selfcoders\website\CustomizedParsedown;
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . "/../bootstrap.php";

$projects = Projects::load();

$parsedown = new CustomizedParsedown;
$filesystem = new Filesystem;

/**
 * @var $project Project
 */
foreach ($projects as $project) {
    $filename = RESOURCES_ROOT . "/projects/" . $project->name . ".md";
    if (!file_exists($filename)) {
        continue;
    }

    $parsedown->baseUrl = sprintf("https://gitlab.com/Programie/%s/-/blob/master/", $project->repoName);
    $parsedown->imageBaseUrl = sprintf("https://gitlab.com/Programie/%s/-/raw/master/", $project->repoName);

    $filesystem->dumpFile(CACHE_ROOT . "/projects-html/" . $project->name . ".html", $parsedown->text(file_get_contents($filename)));
}