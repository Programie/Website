<?php
namespace com\selfcoders\website\controller;

use com\selfcoders\website\TwigRenderer;
use Twig\Error\Error as TwigError;

class ImprintController extends AbstractController
{
    /**
     * @return string
     * @throws TwigError
     */
    public function getContent(): string
    {
        return TwigRenderer::render("imprint");
    }
}