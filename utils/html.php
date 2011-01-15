<?php

/**
 * Encodes string $s as 7-bit ASCII HTML.  (i.e. with named
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
 * http://php.net/manual/en/ref.mbstring.php#mbstring.supported-encodings
 *
 * @param $s string
 * @param $input_charset string
 * @return string 7-bit ASCII string, with HTML entities
 *
 */

function html_encode($s, $input_charset = "UTF-8") {

    // xml_escape() is needed because mb_convert_encoding() seems to
    // treat anything that looks like an entity (irrespective of the
    // value of $input_charset) as a character, refusing to escape it
    // again.  (I don't think this option behaviour should exist (a
    // string is either encoded or it's not), but mbstring supports
    // it so this does too.)

    if ($input_charset != "HTML-ENTITIES") {
        $s = xml_escape($s);
    }
    
    if (extension_loaded("mbstring")) {
        return mb_convert_encoding($s, "HTML-ENTITIES", $input_charset);
    }
    else {
        return htmlentities($s, ENT_NOQUOTES, $input_charset, false);
    }

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

function html_decode($s, $output_charset = "UTF-8") {
    return html_entity_decode($s, ENT_QUOTES, $output_charset);
}

