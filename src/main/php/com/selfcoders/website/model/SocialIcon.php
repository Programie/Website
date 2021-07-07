<?php
namespace com\selfcoders\website\model;

class SocialIcon
{
    public string $name;
    public string $icon;
    public string $title;
    public string $url;

    public function __construct(string $name, string $title, string $url, ?string $icon = null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->url = $url;
        $this->icon = $icon ?? $name;
    }
}