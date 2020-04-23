#! /usr/bin/env php
<?php
use com\selfcoders\website\model\Project;
use com\selfcoders\website\model\Projects;

require_once __DIR__ . "/../bootstrap.php";

$projects = Projects::load();

$parsedown = new Parsedown;

/**
 * @var $project Project
 */
foreach ($projects as $project) {
    $readmeUrl = sprintf("https://gitlab.com/Programie/%s/-/raw/master/README.md", $project->repoName);

    $curl = curl_init($readmeUrl);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true
    ]);

    $readmeContent = curl_exec($curl);

    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($httpStatus !== 200) {
        printf("Request to %s failed with HTTP status %d\n", $readmeUrl, $httpStatus);
        continue;
    }

    file_put_contents(RESOURCES_ROOT . "/projects/" . $project->name . ".md", $readmeContent);
}