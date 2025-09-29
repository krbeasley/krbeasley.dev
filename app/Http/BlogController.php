<?php

namespace App\Http;

use App\Blog\Post;
use Twig\Error\Error;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    protected function __construct()
    {
        parent::__construct();
    }

    /** Returns the blog's home page
     * @throws Error
     * @return Response
     */
    public static function index() : Response
    {
        $c = new BlogController();
        $html = $c->twig->render("pages/blog.html.twig");

        return new Response($html, 200);
    }

    /** Returns a specific blog post for reading.
     *
     * @param Request $request  The user request
     * @param mixed $params     The parameters array
     * @throws Error
     * @return Response
     */
    public static function view(Request $request, mixed $params) : Response
    {
        $slugParam = $params['slug'];
        $c = new BlogController();

        // Find the blog post
        $blogPost = Post::findSlug($slugParam);

        // Blog error page if no matching post can be found
        if (is_null($blogPost)) {
            return new Response(
                $c->twig->render("pages/blog/error.html.twig", ['code' => 404]),
                200
            );
        }

        // Show the blog post otherwise
        $html = $c->twig->render("pages/blog/view.html.twig", [
            'postTitle' => $blogPost->getTitle(),
            'postContent' => $blogPost->getContent(),
            'postKeywords' => $blogPost->getKeywords(),
            'postSlug' => $slugParam,
        ]);

        return new Response($html, 200);
    }
}