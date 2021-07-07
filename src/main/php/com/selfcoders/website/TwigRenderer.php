<?php
namespace com\selfcoders\website;

use com\selfcoders\website\model\Projects;
use com\selfcoders\website\model\SocialIcon;
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

        $socialIcons = [
            new SocialIcon("github", "GitHub", "https://github.com/Programie"),
            new SocialIcon("gitlab", "GitLab", "https://gitlab.com/Programie"),
            new SocialIcon("lastfm", "Last.fm", "https://www.last.fm/user/Programie"),
            new SocialIcon("reddit", "Reddit", "https://www.reddit.com/user/Programie"),
            new SocialIcon("steam", "Steam", "https://steamcommunity.com/id/programiex", "steam-symbol"),
            new SocialIcon("telegram", "Telegram", "https://t.me/Programie"),
            new SocialIcon("twitter", "Twitter", "https://twitter.com/Programie"),
            new SocialIcon("xing", "XING", "https://www.xing.com/profile/Michael_Wieland73"),
            new SocialIcon("youtube", "YouTube", "https://www.youtube.com/channel/UCwRoDwSWCHlNG9lp2YOFg8w")
        ];

        $loader = new FilesystemLoader(VIEWS_ROOT);

        self::$twig = new Environment($loader);

        self::$twig->addExtension(new HtmlExtension);

        self::$twig->addGlobal("currentYear", date("Y"));
        self::$twig->addGlobal("path", $path);
        self::$twig->addGlobal("projects", $projects);
        self::$twig->addGlobal("socialIcons", $socialIcons);

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

            return $project->category === $category;
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