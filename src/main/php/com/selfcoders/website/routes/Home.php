<?php
namespace com\selfcoders\website\routes;

use com\selfcoders\website\TwigRenderer;
use DateTime;

class Home
{
    public function getContent()
    {
        return TwigRenderer::render("home", [
            "age" => (new DateTime)->diff(new DateTime("1991-04-19"))->y
        ]);
    }
}