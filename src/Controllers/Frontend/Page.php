<?php namespace Microblog\Controllers\Frontend;

use Symfony\Component\HttpFoundation\Response;
use Microblog\Db\PageMapper;
use Microblog\Models\Page as PageModel;
use Microblog\Template\FrontendTwigRenderer;

/**
 * Page Controller / Handler class
 * 
 * Controller / handler class dealing with the homepage
 */
class Page
{
    private $response;
    private $renderer;
    private $page;
    private $mapper;

    public function __construct(
        Response $response,
        FrontendTwigRenderer $renderer,
        PageMapper $mapper,
        PageModel $page
    )
    {
        $this->response = $response;
        $this->renderer = $renderer;
        $this->page = $page;
        $this->mapper = $mapper;
    }

    public function show($params)
    {
        $slug = $params['slug'];

        $where = sprintf('`url` = "%s"', $slug);

        if ($this->mapper->findRow($this->page, $where)) {
            $data = [
                //'id' => $this->page->getId(),
                //'url' => $this->page->getUrl(),
                'title' => $this->page->getTitle(),
                'content' => $this->page->getContent()
            ];
        } else {
            $this->response->setStatusCode(404);

            $data = [
                'title' => '404 - Page Not Found',
                'content' => '<h1>Uuupss!</h1><p>Sorry, we could\'t find the page you were looking for! Make sure the URL is correct!</p>'
            ];
        }

        $html = $this->renderer->render('page', $data);
        $this->response->setContent($html);
    }

    public function showIndex()
    {
        $this->show(['slug' => 'index']);
    }
}
