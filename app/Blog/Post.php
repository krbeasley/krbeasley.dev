<?php

namespace App\Blog;

class Post
{
    protected string    $filePath;
    protected string    $title;
    protected string    $slug;
    protected array     $keywords;
    protected ?string   $content;

    public function __construct(
        string $filePath,
        string $title,
        ?string $slug = null,
        array $keywords = [],
        ?string $content = null,
    )
    {
        $this->filePath = $filePath;
        $this->title = $title;
        $this->slug = $slug ?? $title; //todo: This needs to format the slug from the raw title
        $this->keywords = $keywords;
        $this->content = $content;
    }

    /** Find an existing blog post via its slug. Returns the found blog post
     * object or null upon failure.
     *
     * @param string $searchSlug    The slug of the post you are looking for.
     * @return Post|null
     */
    public static function findSlug(string $searchSlug) : Post|null
    {
        $storagePath = dirname(__DIR__, 2) . "/storage/posts/";

        // Fail if the storage directory cannot be located.
        if (!is_dir($storagePath)) return null;

        $blogPostFiles = array();
        foreach (scandir($storagePath) as $filepath) {
            if (!is_dir($filepath)) $blogPostFiles[] = $filepath;
        }

        // Scan the head of all the resulting files.
        foreach ($blogPostFiles as $filePath) {
            $fileHead = Post::parseHead($storagePath . $filePath);

            // check the slug value
            if (array_key_exists("slug", $fileHead)) {
                if ($fileHead['slug'] === $searchSlug) {
                    // Generate the temp blog post object
                    $post = new Post(
                        filePath: $storagePath . $filePath,
                        title: $fileHead['title'],
                        slug: $fileHead['slug'],
                        keywords: explode(',', $fileHead['keywords']),
                        content: null
                    );

                    // load the content to the blog post
                    $post->loadContent();

                    // return the post
                    return $post;
                }
            }
        }

        // return null if we failed to find a matching post object
        return null;
    }

    /** Parse the head of the blog post file into an array of key value pairs.
     * Pairs include the title, slug, and keywords for the blog post. There is
     * no content attached to the resulting post and the content must be loaded
     * later.
     *
     * @param string $filePath
     * @return array
     */
    protected static function parseHead(string $filePath) : array
    {
        if (!$file = fopen($filePath, 'r')) {
            trigger_error("Failed to open stream at assured location.", E_USER_WARNING);
        }

        $lineLimit = 7;             // the header of the files is 7 lines long
        $headContents = array();

        // Loop through the lines of the file creating your array of "file stats"
        for ($i = 0; $i < $lineLimit; $i++) {
            $headContents[] = fgets($file);
        }
        fclose($file);  // close the file stream

        // The first and last element of the file header will be a delimiting line '---'
        // Remove these before creating the "stats" array
        $del[0] = array_shift($headContents);
        $del[1] = array_pop($headContents);

        // Verify that they are both delimiters
        if ($del[0] !== $del[1]) trigger_error("Invalid delimiter detected.");

        // Break the headContents lines into an array
        $pairs = array();
        foreach ($headContents as $line) {
            $parts = explode(":", $line);

            $key = trim($parts[0]);
            $val = trim($parts[1]);

            // Sanitize the keys to all be lowercase
            if (gettype($key) === 'string') {
                $key = strtolower($key);
            }

            // Build the new pairs array
            $pairs[$key] = $val;
        }

        // Return the new array
        return $pairs;
    }

    /** Load an existing but unready blog post's contents from its source file.
     *
     * @return Post
     */
    public function loadContent() : Post
    {
        $lines = array();

        // open the file
        if (!$file = fopen($this->filePath, 'r')) {
            trigger_error("Could not load file contents from assure file stream", E_USER_WARNING);
            return $this;
        }

        $skippedLines = 0;
        while (($line = fgets($file)) !== false) {
            if ($skippedLines <= 7) {
                $skippedLines ++;
                continue;
            }

            $lines[] = $line;
        }

        // Close the file stream
        fclose($file);

        // Todo: Translate the Markdown contents into HTML
        $parsedown = new \Parsedown();
        $fileContents = "";

        foreach ($lines as $line) {
            $fileContents = $fileContents . $parsedown->text($line);
        }

        $this->content = $fileContents;
        return $this;
    }

    public function getContent() : string|null
    {
        return $this->content;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getKeywords() : array
    {
        return $this->keywords;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }

    public function getFilePath() : string
    {
        return $this->filePath;
    }
}