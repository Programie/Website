#! /usr/bin/env php
<?php
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;

require_once __DIR__ . "/../bootstrap.php";

$projects = Projects::load();

$projects->fetchGitLabIds();

/**
 * @var $project Project
 */
foreach ($projects as $project) {
    printf("\n*** %s ***\n", $project->title);

    if ($project->gitlabId === null) {
        printf("Can't find Gitlab project ID for project %s (repository name %s), skipping fetching artifacts from Gitlab\n", $project->name, $project->repoName);
    } else {
        $project->updateDownloadsFromGitlab();

        printf("Found %d download(s)\n", count($project->downloads));
    }
}

$projects->saveSerialized();