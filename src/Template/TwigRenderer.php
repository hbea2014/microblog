<?php namespace Microblog\Template;

use \Twig_Environment;

/**
 * Renderer for Twig template engine
 */
class TwigRenderer implements RendererInterface
{

    private $renderer;

    public function __construct(Twig_Environment $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render($template, $data = [])
    {

        return $this->renderer->render($template, $data);
    }
}