<?php

/**
 * Convert all characters to HTML entities.
 * 
 * @param string $string The string to convert
 * @return string The resulting string
 * @todo Replace this function. Maybe a static class?
 */
function e($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}
