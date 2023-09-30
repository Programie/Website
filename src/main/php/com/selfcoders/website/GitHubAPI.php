<?php
namespace com\selfcoders\website;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class GitHubAPI
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => "https://api.github.com/"
        ]);
    }

    public function getLatestReleaseForRepository(string $repository): ?array
    {
        try {
            return json_decode($this->client->get(sprintf("repos/Programie/%s/releases/latest", $repository))->getBody()->getContents(), true);
        } catch (ClientException $exception) {
            if ($exception->getResponse()->getStatusCode() === 404) {
                return null;
            }

            throw $exception;
        }
    }

    public function getLastUpdate(string $repository, string $branch): ?DateTime
    {
        $data = json_decode($this->client->get(sprintf("repos/Programie/%s/branches/%s", $repository, $branch))->getBody()->getContents(), true);

        $date = $data["commit"]["commit"]["committer"]["date"] ?? null;
        if ($date === null) {
            return null;
        }

        return new DateTime($date);
    }

    public function getSourceDownloadUrl(string $repository, string $branchOrTag): string
    {
        return sprintf('https://github.com/Programie/%s/archive/refs/heads/%s.zip', $repository, $branchOrTag);
    }
}