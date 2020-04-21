<?php
use com\selfcoders\website\routes\Home;
use com\selfcoders\website\TwigRenderer;

require_once __DIR__ . "/../bootstrap.php";

TwigRenderer::init();

$router = new AltoRouter;

$router->map("GET", "/", [Home::class, "getContent"]);

$match = $router->match();

if ($match === false) {
    http_response_code(404);
    echo TwigRenderer::render("error-404");
} else {
    $target = $match["target"];

    $class = new $target[0];
    $method = $target[1];
    echo $class->{$method}($match["params"]);
}