<?php

namespace App\Http;

use Twig\Environment;
use Twig\Error\Error;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController
{
    protected FilesystemLoader $loader;
    protected Environment $twig;

    protected function __construct() {
        $this->loader = new FilesystemLoader(dirname(__DIR__, 2) . "/templates");
        $this->twig = new Environment($this->loader, []);
    }

    /** Returns the home page.
     * @throws Error
     */
    public static function home() : Response
    {
        $c = new PageController();
        $htmlContent = $c->twig->render("pages/home.html.twig", []);

        return new Response($htmlContent, 200);
    }

    /** Returns the about page.
     * @throws Error
     */
    public static function about() : Response
    {
        $c = new PageController();
        $htmlContent = $c->twig->render("pages/about.html.twig", []);

        return new Response($htmlContent, 200);
    }

    /** Returns the hire-me page
     *
     * @return Response
     * @throws Error
     */
    public static function hireMe() : Response
    {
        $c = new PageController();
        $htmlContent = $c->twig->render("pages/hire-me.html.twig", []);

        return new Response($htmlContent, 200);
    }

    /** Returns one of the error pages.
     *
     * @param int $code
     * @return Response
     * @throws Error
     */
    public static function fallback(int $code = 500) : Response
    {
        $c = new PageController();
        $htmlContent = $c->twig->render("error/$code.html.twig");

        return new Response($htmlContent, 200);
    }
}