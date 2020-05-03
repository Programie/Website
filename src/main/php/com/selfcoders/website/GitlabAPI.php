<?php
namespace com\selfcoders\website;

use GuzzleHttp\Client;

class GitlabAPI
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => "https://gitlab.com/api/v4/"
        ]);
    }

    public function getProjectsOfUser(string $username): array
    {
        return json_decode($response = $this->client->get(sprintf("users/%s/projects?per_page=100", $username))->getBody()->getContents(), true);
    }

    public function getReleasesForProject(int $projectId): array
    {
        return json_decode($this->client->get(sprintf("projects/%d/releases", $projectId))->getBody()->getContents(), true);
    }

    public function getBranchesOfProject(int $projectId): array
    {
        return json_decode($this->client->get(sprintf("projects/%d/repository/branches", $projectId))->getBody()->getContents(), true);
    }

    public function getSourceDownloadUrl(string $username, string $repository, string $branchOrTag): string
    {
        return sprintf('https://gitlab.com/%s/%s/-/archive/%s/%2$s-%3$s.tar.gz', $username, $repository, $branchOrTag);
    }
}