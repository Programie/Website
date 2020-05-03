<?php
namespace com\selfcoders\website\model;

use DateTime;

class Download
{
    public string $url;
    public DateTime $date;
    public string $title;

    public function __construct(string $url, DateTime $date, string $title)
    {
        $this->url = $url;
        $this->date = $date;
        $this->title = $title;
    }
}