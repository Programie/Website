#! /usr/bin/env php
<?php
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;

require_once __DIR__ . "/../bootstrap.php";

$projects = Projects::load();

/**
 * @var $project Project
 */
foreach ($projects as $project) {
    printf("\n*** %s ***\n", $project->title);

    $project->updateDownloads();
    printf("Found %d download(s)\n", $project->downloads === null ? 0 : count($project->downloads));
}

$projects->saveSerialized();