<?php

namespace App\Http;

use Twig\Environment;
use Twig\Error\Error;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class Controller
{
    protected FilesystemLoader $loader;
    protected Environment $twig;

    protected function __construct() {
        $this->loader = new FilesystemLoader(dirname(__DIR__, 2) . "/templates");
        $this->twig = new Environment($this->loader, []);
    }
}