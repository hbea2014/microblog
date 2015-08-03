<?php namespace Microblog\Encryption;

/**
 * Hash class
 */
class Hash
{

    /**
     * Create a hash of the given string using a salt
     * 
     * @param string $string The string to hash
     * @param string $salt The salt
     * @return string The hash
     */
    public static function make($string, $salt = '')
    {
        return hash('sha256', $string . $salt);
    }

    /**
     * Create a unique salt of a given length
     * 
     * @param integer $length The length of the salt
     * @return string
     */
    public static function salt($length)
    {
        return mcrypt_create_iv($length);
    }

    /**
     * Create a unique hash
     * 
     * @return string
     */
    public static function unique()
    {
        return self::make(uniqid());
    }
}