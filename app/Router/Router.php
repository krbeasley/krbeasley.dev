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
            new Route("/hire-me", PageController::class, "hireMe", ["GET"]),
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
        $routeIndex = $this->matchRoute($request->getPathInfo());
        if ($routeIndex === -1) {
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
        try {
            call_user_func([$class, $action]);
        } catch (Exception $e) {
            // Get the error code
            $code = $e->getCode();

            // Check if it's a code we know how to handle
            if (!in_array($code, [403, 404, 500], true)) {
                $code = 500; // Set the code to 500 by default.
            }

            call_user_func([PageController::class, "fallback"], $code);
        }
    }

    /** Checks if the router class has an exising route by path. Returns the matching
     * route's index in the routes list or -1 if no matching route can be located.
     *
     * @param string $requestPath
     * @return int
     */
    private function matchRoute(string $requestPath) : int
    {
        // Break down the path into tokens
        $requestParts = explode("/", $requestPath);
        $requestElementCount = count($requestParts);

        /* Loop through each of the loaded routes, splitting them into tokens,
        then comparing each of those tokens with their counterpart supplied by
        the request. */
        for ($routeIndex = 0; $routeIndex < count($this->routes); $routeIndex++) {
            /** @var Route $route */
            $route = $this->routes[$routeIndex];

            // Get the route's tokens
            $routeParts = explode("/", $route->path);
            $routeElementCount = count($routeParts);

            // Ignore this route if the token lists aren't the same length.
            if ($routeElementCount !== $requestElementCount) {
                continue;
            }

            // Start comparing tokens.
            $tokensMatch = true;   // Default to a match and return false early
            for ($i = 0; $i < $routeElementCount; $i++) {
                $routeToken = $routeParts[$i];
                $requestToken = $requestParts[$i];

                // Tokens beginning with : are placeholders and should be
                // ignored for comparison
                if (str_starts_with($routeToken, ":")) {
                    continue;
                }

                // Indicate the tokens did not match
                if ($routeToken !== $requestToken) {
                    $tokensMatch = false;
                }
            }

            // Only return true when all matchable elements are valid
            if ($tokensMatch) {
                return $routeIndex;
            }
        }

        return -1;
    }
}