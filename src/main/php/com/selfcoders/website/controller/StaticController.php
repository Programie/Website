<?php
namespace com\selfcoders\website\controller;

use com\selfcoders\website\TwigRenderer;
use Twig\Error\Error as TwigError;

class StaticController extends AbstractController
{
    /**
     * @return string
     * @throws TwigError
     */
    public function getHome(): string
    {
        return TwigRenderer::render("home");
    }

    /**
     * @return string
     * @throws TwigError
     */
    public function getImprint(): string
    {
        return TwigRenderer::render("imprint");
    }

    /**
     * @return string
     * @throws TwigError
     */
    public function getPrivacyPolicy(): string
    {
        return TwigRenderer::render("privacy-policy");
    }
}