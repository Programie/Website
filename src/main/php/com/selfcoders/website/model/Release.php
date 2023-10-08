<?php
namespace com\selfcoders\website\model;

use DateTime;

class Release
{
    public string $name;
    public ?DateTime $date = null;
    public string $notes;
}