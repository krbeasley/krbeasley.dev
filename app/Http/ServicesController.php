<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\Response;

class ServicesController extends Controller
{
    public static function googleWorkspace() : Response
    {
        $c = new ServicesController();
        $html = $c->twig->render("pages/services/google.html.twig");

        return new Response($html, Response::HTTP_OK);
    }

    public static function webDevelopment() : Response
    {
        $c = new ServicesController();
        $html = $c->twig->render("pages/services/webdev.html.twig");

        return new Response($html, Response::HTTP_OK);
    }

    public static function customSolutions() : Response
    {
        $c = new ServicesController();
        $html = $c->twig->render("pages/services/solutions.html.twig");

        return new Response($html, Response::HTTP_OK);
    }
}
