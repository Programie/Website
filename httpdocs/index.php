<?php
use com\selfcoders\website\controller\AboutController;
use com\selfcoders\website\controller\HomeController;
use com\selfcoders\website\controller\ImprintController;
use com\selfcoders\website\controller\PrivacyPolicyController;
use com\selfcoders\website\controller\ProjectsController;
use com\selfcoders\website\exception\ForbiddenException;
use com\selfcoders\website\exception\NotFoundException;
use com\selfcoders\website\model\Projects;
use com\selfcoders\website\TwigRenderer;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;

require_once __DIR__ . "/../bootstrap.php";

$assetsPackage = new Package(new JsonManifestVersionStrategy(APP_ROOT . "/webpack.assets.json"));

$projects = Projects::loadSerialized();

TwigRenderer::init($assetsPackage, $projects);

$router = new AltoRouter;

$router->addMatchTypes([
    "noslash" => "[^/]+"
]);

$router->map("GET", "/", [HomeController::class, "getContent"]);
$router->map("GET", "/about", [AboutController::class, "getContent"]);
$router->map("GET", "/imprint", [ImprintController::class, "getContent"]);
$router->map("GET", "/privacy-policy", [PrivacyPolicyController::class, "getContent"]);
$router->map("GET", "/projects", [ProjectsController::class, "listProjects"]);
$router->map("GET", "/projects/[noslash:name]", [ProjectsController::class, "showProject"]);
$router->map("GET", "/projects/[noslash:name]/[**:resource]", [ProjectsController::class, "getResource"]);
$router->map("POST", "/projects/[noslash:name]/update", [ProjectsController::class, "update"]);

$match = $router->match();

if ($match === false) {
    http_response_code(404);
    echo TwigRenderer::render("error-404");
} else {
    $target = $match["target"];

    $class = new $target[0]($projects);
    $method = $target[1];
    try {
        $response = $class->{$method}($match["params"]);
        if ($response !== null) {
            echo $response;
        }
    } catch (ForbiddenException $exception) {
        http_response_code(403);
        echo TwigRenderer::render("error-403");
    } catch (NotFoundException $exception) {
        http_response_code(404);
        echo TwigRenderer::render("error-404");
    }
}