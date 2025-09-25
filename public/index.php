<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once dirname(__DIR__) . "/vendor/autoload.php";

use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Cheechstack\Routing\Router;
use Cheechstack\Routing\Route;
use App\Http\PageController;

// Load the ENV file
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Build the request
$request = Request::createFromGlobals();

// Build the router object
$router = new Router();

function test() {return 200;}

// Create the allowed routes
$routes = [
    new Route('/', "GET", [PageController::class, 'home']),
    new Route('/about', "GET", [PageController::class, 'about']),
    new Route('/hire-me', "GET", [PageController::class, 'hireMe']),
    new Route('/status', "GET", fn() => test()),
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
    (PageController::fallback($response->getStatusCode()))->send();
}
