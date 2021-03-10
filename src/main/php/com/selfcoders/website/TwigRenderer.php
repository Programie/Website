<?php
namespace com\selfcoders\website;

use com\selfcoders\website\model\Projects;
use Symfony\Component\Asset\Package;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Extra\Html\HtmlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigRenderer
{
    private static ?Environment $twig = null;

    /**
     * @param Package $assetsPackage
     * @param Projects $projects
     */
    public static function init(Package $assetsPackage, Projects $projects): void
    {
        if (self::$twig !== null) {
            return;
        }

        if (isset($_SERVER["PATH_INFO"])) {
            $path = $_SERVER["PATH_INFO"];
        } else {
            $path = "";
        }

        $loader = new FilesystemLoader(VIEWS_ROOT);

        self::$twig = new Environment($loader);

        self::$twig->addExtension(new HtmlExtension);

        self::$twig->addGlobal("currentYear", date("Y"));
        self::$twig->addGlobal("path", $path);
        self::$twig->addGlobal("projects", $projects);

        self::$twig->addFunction(new TwigFunction("asset", function (string $path) use ($assetsPackage) {
            return $assetsPackage->getUrl($path);
        }));

        self::$twig->addFunction(new TwigFunction("isActivePage", function (string $url, bool $isRegex = false) use ($path) {
            if ($isRegex) {
                return preg_match(sprintf("|%s|", $url), $path);
            }

            return $url === $path;
        }));

        self::$twig->addFunction(new TwigFunction("isActiveProjectCategory", function (string $category) use ($path, $projects) {
            $pathParts = explode("/", trim($path, "/"));

            if (count($pathParts) < 2) {
                return false;
            }

            if ($pathParts[0] !== "projects") {
                return false;
            }

            $project = $projects->byName($pathParts[1]);
            if ($project === null) {
                return false;
            }

            return $project->type === $category;
        }));

        if (USE_CACHE) {
            self::$twig->setCache(TWIG_CACHE_ROOT);
        }
    }

    /**
     * @param $name
     * @param array $context
     * @return string
     * @throws Error
     */
    public static function render($name, $context = []): string
    {
        return self::$twig->render($name . ".twig", $context);
    }
}