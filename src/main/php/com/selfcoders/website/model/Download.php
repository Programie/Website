<?php
namespace com\selfcoders\website\model;

class Download
{
    public string $url;
    public string $title;

    public function __construct(string $url, string $title)
    {
        $this->url = $url;
        $this->title = $title;
    }
}