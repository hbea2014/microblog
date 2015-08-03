<?php namespace Microblog\Form;

/**
 * Input class
 */
class Input
{

    /**
     * Check if an input was submitted
     * 
     * @param string $type
     * @return boolean
     */
    public static function exists($type = 'post')
    {
        switch($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * Get the submitted input
     * 
     * @param type $item The name of the submitted data
     * @return string The value submitted
     */
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }
        
        return '';
    }
}