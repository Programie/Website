<?php
namespace com\selfcoders\website\controller;

use com\selfcoders\website\TwigRenderer;
use DateTime;
use Twig\Error\Error as TwigError;

class AboutController extends AbstractController
{
    /**
     * @return string
     * @throws TwigError
     */
    public function getContent(): string
    {
        return TwigRenderer::render("about", [
            "age" => (new DateTime)->diff(new DateTime("1991-04-19"))->y
        ]);
    }
}