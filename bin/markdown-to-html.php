#! /usr/bin/env php
<?php
use com\selfcoders\website\CustomizedParsedown;
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . "/../bootstrap.php";

$projects = Projects::load();

$filesystem = new Filesystem;

/**
 * @var $project Project
 */
foreach ($projects as $project) {
    $projectPath = sprintf("%s/projects/%s", RESOURCES_ROOT, $project->name);
    $outputPath = sprintf("%s/projects-html/%s", CACHE_ROOT, $project->name);
    $resourcesPath = sprintf("%s/httpdocs/projects/%s", APP_ROOT, $project->name);

    if (!is_dir($projectPath)) {
        continue;
    }

    // Ensure resources output folder is empty
    if (file_exists($resourcesPath)) {
        $filesystem->remove($resourcesPath);
    }

    $parsedown = new CustomizedParsedown;

    $parsedown->baseUrl = sprintf("/projects/%s", $project->name);

    $parsedown->relativeLinkHook = function ($filename) use ($projectPath, $resourcesPath, $filesystem) {
        $sourceFile = sprintf("%s/%s", $projectPath, $filename);

        if (file_exists($sourceFile)) {
            $targetFilename = sprintf("%s.%s", md5_file($sourceFile), strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION)));

            $filesystem->copy($sourceFile, sprintf("%s/%s", $resourcesPath, $targetFilename));
        } else {
            printf("Could not find project resource file: %s\n", $sourceFile);
            $targetFilename = $filename;
        }

        return $targetFilename;
    };

    $filesystem->dumpFile(sprintf("%s/index.html", $outputPath), $parsedown->text(file_get_contents(sprintf("%s/index.md", $projectPath))));
}