<?php

namespace App\Router;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use App\Http\PageController;

class Router
{
    private array $routes = array();
    public function __construct()
    {
        $this->initRoutes();
    }

    /** Initialize the initial routes for the application. */
    private function initRoutes() : void
    {
        $this->routes = [
            new Route("/", PageController::class, "home", ["GET"]),
            new Route("/about", PageController::class, "about", ["GET"]),
        ];
    }

    /** Handle an incoming request
     *
     * @param Request $request
     * @throws Exception
     */
    public function handle(Request $request) : void
    {
        // Make sure the router has the requested route
        if (($routeIndex = $this->matchRoute($request->getPathInfo())) === -1) {
            call_user_func([PageController::class, "fallback"], 404);
            return;
        }

        $targetRoute = $this->routes[$routeIndex];
        $class = $targetRoute->controller;
        $action = $targetRoute->action;
        $allowedMethods = $targetRoute->methods;

        // Check that the requested method is allowed on the route
        if (!in_array($request->getMethod(), $allowedMethods)) {
            call_user_func([PageController::class, "fallback"], 405);
            return;
        }

        // Throw a 500 error if the route's controller and action somehow don't exist.
        if (!method_exists($class, $action)) {
            call_user_func([PageController::class, "fallback"], 500);
            return;
        }

        // Run the route's action
        call_user_func([$class, $action]);
    }

    /** Checks if the router class has an exising route by path. Returns the matching
     * route's index in the routes list or -1 if no matching route can be located.
     *
     * @param string $requestPath
     * @return int
     */
    private function matchRoute(string $requestPath) : int
    {
        $requestParts = explode("/", $requestPath);
        $requestElementCount = count($requestParts);

        for ($routeIndex = 0; $routeIndex < count($this->routes); $routeIndex++) {
            /** @var Route $route */
            $route = $this->routes[$routeIndex];

            $routeParts = explode("/", $route->path);
            $routeElementCount = count($routeParts);

            // ignore this iteration if the paths don't match in element count
            if ($routeElementCount !== $requestElementCount) {
                continue;
            }

            // check each of the route parts to see if they match their
            // request counterparts
            $tokensMatch = true;
            for ($i = 0; $i < $routeElementCount; $i++) {
                $routeToken = $routeParts[$i];
                $requestToken = $requestParts[$i];
                // tokens starting with : are placeholders and should be ignored
                // for comparison
                if (str_starts_with($routeToken, ":")) {
                    continue;
                }

                // indicate the routes didn't match at some point
                if ($routeToken !== $requestToken) {
                    $tokensMatch = false;
                }
            }

            // only return true when all matchable elements are valid
            if ($tokensMatch) {
                return $routeIndex;
            }
        }

        return -1;
    }
}