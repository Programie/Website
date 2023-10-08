#! /usr/bin/env php
<?php
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;

require_once __DIR__ . "/../bootstrap.php";

function updateProject(Project $project)
{
    printf("\n*** %s ***\n", $project->title);

    $project->updateData();
    printf("Found %d download(s)\n", $project->downloads === null ? 0 : count($project->downloads));
}

$projectsToUpdate = array_slice($argv, 1);

if (empty($projectsToUpdate)) {
    $projects = Projects::load();

    /**
     * @var $project Project
     */
    foreach ($projects as $project) {
        updateProject($project);
    }
} else {
    $projects = Projects::loadSerialized();

    foreach ($projectsToUpdate as $projectName) {
        $project = $projects->byName($projectName);
        if ($project === null) {
            printf("Project %s not found!\n", $projectName);
        } else {
            updateProject($project);
        }
    }
}

$projects->saveSerialized();