<?php
namespace com\selfcoders\website\controller;

use com\selfcoders\website\TwigRenderer;

class ImprintController
{
    public function getContent()
    {
        return TwigRenderer::render("imprint");
    }
}