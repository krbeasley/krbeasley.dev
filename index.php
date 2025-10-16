<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . "/vendor/autoload.php";

use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Cheechstack\Routing\Router;
use Cheechstack\Routing\Route;
use App\Http;

// Load the ENV file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Build the request
$request = Request::createFromGlobals();

// Build the router object
$router = new Router();

// Create the allowed routes
$routes = [
    // Generic Static Routes
    new Route('/', "GET", [Http\PageController::class, 'home']),
    new Route('/about', "GET", [Http\PageController::class, 'about']),
    new Route('/hire-me', "GET", [Http\PageController::class, 'hireMe']),

    // Blog Routes
    new Route('/blog', "GET", [Http\BlogController::class, 'index']),
    new Route('/blog/:slug', "GET", [Http\BlogController::class, 'view']),

    // Services Routes
    new Route('/workspace-integrations', "GET", [Http\ServicesController::class, 'googleWorkspace']),
    new Route('/web-development', "GET", [Http\ServicesController::class, 'webDevelopment']),
    new Route('/custom-solutions', "GET", [Http\ServicesController::class, 'customSolutions']),

    // Tools
    new Route('/tools', "GET", [Http\ToolsController::class, 'index']),
];

// Load the routes into the router
$router->add($routes);

// Get the Router's response
$response = $router->handle($request);

// Return the response if all went well.
if ($response->getStatusCode() === 200) {
    $response->send();
}
// Return error fallback otherwise
else {
    (Http\PageController::fallback($response->getStatusCode()))->send();
}
