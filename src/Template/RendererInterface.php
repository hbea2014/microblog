<?php namespace Microblog\Template;

/**
 * Renderer interface
 */
interface RendererInterface
{

    public function render($template, $data = []);
}
