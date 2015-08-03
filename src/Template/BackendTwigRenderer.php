<?php namespace Microblog\Template;

use Microblog\Config;

/**
 * BackendTwigRenderer
 * 
 * BackendRenderer for the Twig template engine
 */
class BackendTwigRenderer extends MicroblogTwigRenderer implements RendererInterface
{
    protected $templatePath = 'backend';
}
