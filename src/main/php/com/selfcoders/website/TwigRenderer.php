<?php
namespace com\selfcoders\website;

use com\selfcoders\website\model\Projects;
use Exception;
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
     * @throws Exception
     */
    public static function init()
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

        $projects = Projects::loadSerialized();

        self::$twig->addExtension(new HtmlExtension);

        self::$twig->addGlobal("currentYear", date("Y"));
        self::$twig->addGlobal("path", $path);
        self::$twig->addGlobal("projects", $projects);

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

        if (strtolower(getenv("TWIG_CACHE")) !== "false") {
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