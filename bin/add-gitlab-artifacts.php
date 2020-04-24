#! /usr/bin/env php
<?php
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;
use GuzzleHttp\Client;

require_once __DIR__ . "/../bootstrap.php";

$projects = Projects::load();

$gitlabClient = new Client([
    "base_uri" => "https://gitlab.com/api/v4/"
]);

$gitlabProjects = json_decode($response = $gitlabClient->get("users/Programie/projects?per_page=100")->getBody()->getContents(), true);

$repoIds = [];

foreach ($gitlabProjects as $gitlabProject) {
    $repoName = $gitlabProject["path"];

    $repoIds[$repoName] = $gitlabProject["id"];
}

/**
 * @var $project Project
 */
foreach ($projects as $project) {
    $gitLabProjectId = $repoIds[$project->repoName] ?? null;

    if ($gitLabProjectId === null) {
        printf("Can't find Gitlab project ID for project %s (repository name %s)\n", $project->name, $project->repoName);
        continue;
    }

    if ($project->gitlabCIUseArtifacts) {
        $tags = json_decode($gitlabClient->get(sprintf("projects/%d/repository/tags", $gitLabProjectId))->getBody()->getContents(), true);

        $project->downloads = [];

        foreach ($tags as $tag) {
            $tagName = $tag["name"];
            $date = $tag["commit"]["created_at"];

            $artifactPath = $project->gitlabCIArtifactPath;
            if ($artifactPath === null) {
                $artifactPath = "download";
            } else {
                $artifactPath = sprintf("raw/%s", $artifactPath);
            }

            $project->downloads[] = [
                "url" => sprintf("https://gitlab.com/Programie/%s/-/jobs/artifacts/%s/%s?job=%s", $project->repoName, $tagName, $artifactPath, $project->gitlabCIJob),
                "date" => $date,
                "title" => $tagName
            ];
        }

        printf("Found %d download(s) for project %s\n", count($project->downloads), $project->name);
    }
}

$projects->saveSerialized();