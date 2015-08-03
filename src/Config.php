<?php namespace Microblog;

use Symfony\Component\Yaml\Parser;

/**
 * Configuration class
 * 
 * Retrieve the configuration from the config file, ie. config.yaml
 */
class Config
{

    /**
     * @var Symfony\Component\Yaml\Parser
     */
    private $parser;

    /**
     * @var array
     */
    private $config;

    /**
     * Constructor
     * 
     * @param \Symfony\Component\Yaml\Parser $parser
     * @param string $configFilePath The path of the config file
     */
    public function __construct(Parser $parser, $configFilePath)
    {
        $this->parser = $parser;
        $this->config = $this->parse($configFilePath);
    }
    
    /**
     * Parse the config file
     * 
     * @param string $filePath
     * @return array The config as an array
     * @throws \Exception
     * @todo if ('1' !== ini_get('allow_url_fopen')) clause: Should it be put somewhere else? Maybe in a file, testing this kind of thing systematically when running the app, and not 'burried' here...
     */
    public function parse($filePath)
    {
        if ( ! file_exists($filePath) ) {
            throw new \Exception('Config file does not exist.');
        }

        if ('1' !== ini_get('allow_url_fopen')) {
            throw new \Exception('Cannot open config file (allow_url_fopen disabled).');
        }

        $fileContent = file_get_contents($filePath); 

        if (false === $fileContent) {
            throw new \Exception('Cannot get config file\'s content.');
        }

        if (0 === strlen($fileContent)) {
            throw new \Exception('Config file empty.');
        }

        return $this->parser->parse($fileContent);
    }

    /**
     * Get the config value
     * 
     * @param null|string $path
     * @return boolean
     */
    public function get($path = null)
    {
        if ($path) {
            $config = $this->config;

            $path = explode('/', $path);

            foreach ($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }

            return $config;

        }
        
        return false;
    }
    
}