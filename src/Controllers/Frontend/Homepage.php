<?php namespace Microblog\Controllers\Frontend;

use Microblog\Template\FrontendRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Homepage Controller / Handler class
 * 
 * Controller / handler class dealing with the homepage
 */
class Homepage
{
    private $request;
    private $response;
    private $renderer;

    public function __construct(
        Request $request,
        Response $response,
        FrontendRenderer $renderer
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
    }

    public function show()
    {
        $data = [
            'name' => $this->request->get('name', 'unlogged user')
        ];
        //$html = $this->renderer->render('Hello {{name}}', $data);
        $html = $this->renderer->render('index', $data);
        $this->response->setContent($html);
    }
}
