<?php namespace Microblog;

use Microblog\Config;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Parser;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @test
     * @covers Microblog\Config::parse
     * @expectedException \Exception
     * @expectedExceptionMessage Config file does not exist
     */
    public function parse_throws_Exception_for_unexisting_file()
    {
        $parser = new Parser();

        // Setup a root directory
        $root = vfsStream::setup();

        // Set a path of a non existing file
        $configFilePath = $root->url() . '/file';

        new Config($parser, $configFilePath);
        // Create a config mock
        /*
        $configMock = $this->getMockBuilder('Microblog\Config')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $configMock->parse($filePath);
         * 
         */

    }
    
    /**
     * @test
     * @covers Microblog\Config::parse
     * @expectedException \Exception
     * @expectedExceptionMessage Config file empty
     */
    public function parse_throws_Exception_for_empty_file()
    {
        $parser = new Parser();

        // Setup a root directory
        $root = vfsStream::setup();

        // Create an empty file
        $file = vfsStream::newFile('config.yaml')
            ->at($root);

        // Set a path of a non existing file
        $configFilePath = $file->url();

        new Config($parser, $configFilePath);
    }

    /**
     * @test
     * @covers Microblog\Config::parse
     * @expectedException Symfony\Component\Yaml\Exception\ParseException
     */
    public function parse_throws_Exception_for_incorrect_yaml_file()
    {
        // Setup a root directory
        $root = vfsStream::setup();

        // Create a file with content in the root directory
        $file = vfsStream::newFile('config.yaml')
            ->withContent(<<<EOF
skd: {]}
chien: 
EOF
            )
            ->at($root);

        $configFilePath = $file->url();
        $parser = new Parser();

        //var_dump(file_get_contents($configFilePath));
        $config = new Config($parser, $configFilePath);

        var_dump($config->get('whoever'));
    }

    /**
     * @test
     * @covers Microblog\Config::get
     * @param string $content
     * @param mixed $expectedValue
     * @param string $param
     * @dataProvider provider_get_returns_correct_config_values()
     */
    public function get_returns_correct_config_values($content, $expectedValue, $param)
    {
        // Setup a root directory
        $root = vfsStream::setup();

        // Create a file with content in the root directory
        $file = vfsStream::newFile('config.yaml')
            ->withContent($content)
            ->at($root);

        // Get the file url
        $configFilePath = $file->url();

        $parser = new Parser();

        $config = new Config($parser, $configFilePath);

        $this->assertEquals($expectedValue, $config->get($param));
    }

    public function provider_get_returns_correct_config_values()
    {
        return [
            [
                // content
                <<<EOF
param1: value1
param2: value2
param3: value3
EOF
                ,
                // expected value
                'value2',
                // parameter
                'param2'
            ],
            [
                // content
                <<<EOF
'param1':
 param1a: value1a
 param1b: value1b
 param1c: value1c
param2: value2
param3: value3
EOF
                ,
                // expected value
                ['param1a' => 'value1a', 'param1b' => 'value1b','param1c' => 'value1c'],
                // parameter
                'param1'
            ],
            [
                // content
                <<<EOF
'param1':
 param1a: value1a
 param1b: value1b
 param1c: value1c
param2: value2
param3: value3
EOF
                ,
                // expected value
                'value1b',
                // parameter
                'param1/param1b'
            ],
        ];
    }
}