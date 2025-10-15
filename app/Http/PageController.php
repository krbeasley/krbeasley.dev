<?php

namespace App\Http;

use Twig\Error\Error;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    protected function __construct() {
        parent::__construct();
    }

    /** Returns the home page.
     * @throws Error
     */
    public static function home() : Response
    {
        $service_card_data = [
            [
                "card_title" => "Google Workspace Tools & Automation",
                "card_text" => "Automate tasks and connect Google Workspace with your business tools. From setup to ongoing support, I streamline workflows that save time and boost productivity.",
                "img_path" => "/public/images/icons/google_workspace_logo.png",
                "img_alt" => "Google Workspace Logo",
                "button_link" => "/projects",
                "button_text" => "Explore Examples",
            ],
            [
                "card_title" => "Full Stack Web Development",
                "card_text" => "Custom websites and web apps built from front to back. I also provide hosting and DNS management, delivering fast, secure, and scalable solutions tailored to your business needs.",
                "img_path" => "/public/images/icons/server.svg",
                "img_alt" => "Server SVG Icon",
                "button_link" => "/projects",
                "button_text" => "View Projects",
            ],
            [
                "card_title" => "Custom Software & Systems Solutions",
                "card_text" => "From integrations to automation, I turn your ideas into working systems using the right mix of code, tools, and technology to deliver reliable, efficient results.",
                "img_path" => "/public/images/icons/network-right.svg",
                "img_alt" => "Network right icon",
                "button_link" => "/projects",
                "button_text" => "Explore the Possibilities",
            ],
        ];

        $c = new PageController();
        $htmlContent = $c->twig->render("pages/home.html.twig", [
            "service_cards" => $service_card_data,
        ]);

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
