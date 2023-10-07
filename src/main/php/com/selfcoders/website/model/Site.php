<?php
namespace com\selfcoders\website\model;

class Site
{
    public string $type;
    public string $title;
    public string $url;
    public array $iconClasses = [];

    public static function fromTypeValue(string $type, string $value): self
    {
        $site = new self;

        $site->type = $type;

        switch ($type) {
            case "curseforge":
                $site->title = "CurseForge";
                $site->url = sprintf("https://www.curseforge.com/minecraft/bukkit-plugins/%s", $value);
                break;
            case "dockerhub":
                $site->title = "DockerHub";
                $site->url = sprintf("https://hub.docker.com/r/%s", $value);
                $site->iconClasses = ["fa-brands", "fa-docker"];
                break;
            case "github":
                $site->title = "GitHub";
                $site->url = sprintf("https://github.com/%s", $value);
                $site->iconClasses = ["fa-brands", "fa-github"];
                break;
            case "modrinth":
                $site->title = "Modrinth";
                $site->url = sprintf("https://modrinth.com/plugin/%s", $value);
                break;
            case "packagist":
                $site->title = "Packagist";
                $site->url = sprintf("https://packagist.org/packages/%s", $value);
                $site->iconClasses = ["fa-brands", "fa-php"];
                break;
        }

        return $site;
    }
}