<?php
require_once __DIR__ . "/vendor/autoload.php";

define("APP_ROOT", __DIR__);
define("RESOURCES_ROOT", APP_ROOT . "/src/main/resources");
define("VIEWS_ROOT", RESOURCES_ROOT . "/views");
define("CACHE_ROOT", APP_ROOT . "/cache");
define("TWIG_CACHE_ROOT", CACHE_ROOT . "/twig");

$useCacheEnv = getenv("USE_CACHE");
if ($useCacheEnv === false) {
    $useCacheEnv = "true";
}
define("USE_CACHE", $useCacheEnv !== "false");