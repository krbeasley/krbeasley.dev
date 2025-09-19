<?php

namespace App\Http;

use Twig\Environment;
use Twig\Error\Error;
use Twig\Loader\FilesystemLoader;

class PageController
{
    protected FilesystemLoader $loader;
    protected Environment $twig;
    protected function __construct() {

        $this->loader = new FilesystemLoader(dirname(__DIR__, 2) . "/templates");
        $this->twig = new Environment($this->loader, []);
    }

    /** Returns the home page
     * @throws Error
     */
    public static function home() : void
    {
        $c = new PageController();
        echo $c->twig->render("pages/home.html.twig", []);
    }

    /** Returns the about page
     *
     * @throws Error
     */
    public static function about() : void
    {
        $c = new PageController();
        echo $c->twig->render("pages/about.html.twig", []);
    }

    /** Echo the HTML of the error pages with the correct code.
     *
     * @param int $code
     * @throws Error
     */
    public static function fallback(int $code = 500) : void
    {
        $c = new PageController();
        echo $c->twig->render("error/$code.html.twig");
    }
}