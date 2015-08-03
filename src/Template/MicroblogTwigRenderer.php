<?php namespace Microblog\Template;

use Microblog\Config;

/**
 * MicroblogTwigRenderer
 * 
 * Renderer for the Twig template engine
 */
class MicroblogTwigRenderer implements RendererInterface
{

    private $config;
    private $renderer;
    protected $templatePath;

    public function __construct(Config $config, RendererInterface $renderer)
    {
        $this->config = $config;
        $this->renderer = $renderer;
    }

    public function render($template, $data = [])
    {
        $templatePath = (is_null($this->templatePath)) 
            ? $template . ".html"
            : $this->templatePath . "/" . $template . ".html";

        $data = array_merge($data, [
            'appName' => $this->config->get('appName'),
            'appUrl' => $this->config->get('appUrl')
        ]);

        return $this->renderer->render($templatePath, $data);
    }
}
