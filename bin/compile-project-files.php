#! /usr/bin/env php
<?php
use com\selfcoders\website\CustomizedParsedown;
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . "/../bootstrap.php";

$projects = Projects::load();

$projects->fetchGitLabIds();

$filesystem = new Filesystem;

/**
 * @var $project Project
 */
foreach ($projects as $project) {
    printf("*** %s ***\n", $project->title);

    if ($project->gitlabCIUseArtifacts) {
        if ($project->gitlabId === null) {
            printf("Can't find Gitlab project ID for project %s (repository name %s), skipping fetching artifacts from Gitlab\n", $project->name, $project->repoName);
        } else {
            $project->fetchGitlabArtifacts();

            printf("Found %d download(s)\n", count($project->downloads));
        }
    }

    $projectPath = sprintf("%s/projects/%s", RESOURCES_ROOT, $project->name);
    $outputPath = sprintf("%s/projects-html/%s", CACHE_ROOT, $project->name);
    $resourcesPath = sprintf("%s/httpdocs/projects/%s", APP_ROOT, $project->name);
    $coverImagePath = sprintf("%s/%s", $projectPath, $project->coverImage);

    if (!is_dir($projectPath)) {
        continue;
    }

    // Ensure resources output folder is empty
    if (file_exists($resourcesPath)) {
        $filesystem->remove($resourcesPath);
    }

    $resources = [];

    $parsedown = new CustomizedParsedown;

    $parsedown->baseUrl = sprintf("/projects/%s", $project->name);

    $parsedown->relativeLinkHook = function ($filename) use ($projectPath, $resourcesPath, &$resources, $filesystem) {
        $sourceFile = sprintf("%s/%s", $projectPath, $filename);

        if (file_exists($sourceFile)) {
            $targetFilename = sprintf("%s.%s", md5_file($sourceFile), strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION)));

            $resources[$targetFilename] = $sourceFile;
        } else {
            printf("Could not find project resource file: %s\n", $sourceFile);
            $targetFilename = $filename;
        }

        return $targetFilename;
    };

    $filesystem->dumpFile(sprintf("%s/index.html", $outputPath), $parsedown->text(file_get_contents(sprintf("%s/index.md", $projectPath))));

    if (file_exists($coverImagePath)) {
        $filename = sprintf("%s.%s", md5_file($coverImagePath), strtolower(pathinfo($coverImagePath, PATHINFO_EXTENSION)));

        $project->coverImage = $filename;

        $resources[$filename] = $coverImagePath;
    }

    foreach ($resources as $targetFilename => $sourceFile) {
        $targetFile = sprintf("%s/%s", $resourcesPath, $targetFilename);

        printf("Copying project resource file %s to %s\n", $sourceFile, $targetFile);
        $filesystem->copy($sourceFile, $targetFile);
    }

    echo "\n";
}

$projects->saveSerialized();