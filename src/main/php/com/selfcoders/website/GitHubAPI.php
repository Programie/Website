<?php
namespace com\selfcoders\website;

use DateTime;
use Github\Client;
use Github\Exception\RuntimeException;

class GitHubAPI
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function getLatestReleaseForRepository(string $repository): ?array
    {
        try {
            return $this->client->repository()->releases()->latest("Programie", $repository);
        } catch (RuntimeException $exception) {
            if ($exception->getCode() === 404) {
                return null;
            }

            throw $exception;
        }
    }

    public function getLastUpdate(string $repository, string $branch): ?DateTime
    {
        $data = $this->client->repository()->branches("Programie", $repository, $branch);

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