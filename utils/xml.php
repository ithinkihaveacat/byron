<?php

/**
 * Escapes (replaces) the "special" XML characters (&, ", ', <, >) with
 * their corresponding entities.
 *
 * @param $s string
 * @param $input_charset
 * @return string
 *
 */

function xml_escape($s, $input_charset = "UTF-8") {
    return htmlspecialchars($s, ENT_QUOTES, $input_charset);
}

/**
 * Unescapes (replaces) the entities corresponding to "special" XML
 * characters (&, ", ', <, >) with the characters themselves.
 *
 * ? The input charset is ISO-8859-1?  Does it matter?
 *
 * @param string $s
 * @return string
 */

function xml_unescape($s) {
    return htmlspecialchars_decode($s, ENT_QUOTES);
}

/**
 * Encodes string $s as 7-bit ASCII XML.  (i.e. with numeric
 * entities.)
 * 
 * If the $input_charset is "HTML-ENTITIES", existing entities are
 * untouched.  If the $input_charset is anything else, existing
 * entities are effectively ignored such that e.g. "&amp;" is 
 * converted to "&amp;amp;".
 * 
 * This function requires the mbstring module.  (The mbstring module
 * is a little weird; I'd prefer that it be rewritten using iconv or
 * pure PHP.) A list of supported encodings is at:
 * 
 * @param $s string
 * @param $input_charset string
 * @return string 7-bit ASCII string, with XML entities
 */

function xml_encode($s, $input_charset = "UTF-8")
{
    $tmp = new \Byron\String($s, $input_charset);
    return $tmp->toNumericEntities();
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

function xml_decode($s, $output_charset = "UTF-8") {
    $tmp = new \Byron\String($s);
    return $tmp->fromNumericEntities($output_charset);
}
