<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\Response;

class ToolsController extends Controller
{
    public static function index(): Response
    {
        $c = new ToolsController();
        $html = $c->twig->render("pages/tools/index.html.twig");

        return new Response($html, 200);
    }
}
