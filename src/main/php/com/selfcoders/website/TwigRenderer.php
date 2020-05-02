<?php
namespace com\selfcoders\website;

use com\selfcoders\website\model\Projects;
use Exception;
use Symfony\Component\Asset\Package;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Extra\Html\HtmlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigRenderer
{
    /**
     * @var Environment
     */
    public static $twig;

    /**
     * @param Package $assetsPackage
     * @param Projects $projects
     */
    public static function init(Package $assetsPackage, Projects $projects)
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

        self::$twig->addFunction(new TwigFunction("isActivePage", function (string $url) use ($path) {
            $urlParts = explode("/", trim($url, "/"));
            $pathParts = explode("/", trim($path, "/"));

            foreach ($urlParts as $index => $part) {
                if (!isset($pathParts[$index])) {
                    return false;
                }

                if ($pathParts[$index] !== $part) {
                    return false;
                }
            }

            return true;
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
    public static function render($name, $context = [])
    {
        return self::$twig->render($name . ".twig", $context);
    }
}