<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once dirname(__DIR__) . "/vendor/autoload.php";

use Dotenv\Dotenv;

// Load the ENV file
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Build the request
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

// Load the router
$router = new \App\Router\Router(); // Load the router

// Process the request
try {
    $router->handle($request);
} catch (\Exception $e) {
    echo $e->getMessage();
}

