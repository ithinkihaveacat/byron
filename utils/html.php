<?php

/**
 * Encodes string $s as 7-bit ASCII HTML.  (i.e. with named
 * entities.)
 * 
 * @param $s string
 * @param $input_charset string
 * @return string 7-bit ASCII string, with HTML entities
 *
 */

function html_encode($s, $input_charset = "UTF-8")
{
    $tmp = new \Byron\String($s, $input_charset);
    return $tmp->toNamedEntities();
}

/**
 * Converts 7-bit ASCII HTML encoded string (i.e. with entities) into
 * the specified character set.
 *
 * Note: both named and numeric entities will be decoded.
 *
 * @param string $s 7-bit ASCII HTML encoded string
 * @param string $output_charset
 * @return string
 */

function html_decode($s, $output_charset = "UTF-8")
{
    $tmp = new \Byron\String($s);
    return $tmp->fromNamedEntities($output_charset);
}

