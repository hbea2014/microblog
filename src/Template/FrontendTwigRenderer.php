<?php namespace Microblog\Template;

use Microblog\Config;

/**
 * FrontendTwigRenderer
 * 
 * FrontendRenderer for the Twig template engine
 */
class FrontendTwigRenderer extends MicroblogTwigRenderer implements RendererInterface
{
    protected $templatePath = 'frontend';
}
