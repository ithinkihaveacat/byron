<?php

// $Id$

/**
 * Converts string $s into a form suitable for use in a URL/URI.
 * (i.e. as specified by RFC 1738.)  The character set of $s is
 * irrelevant (the encoding is done in binary).
 * 
 * @param string $s treated as binary
 * @return string %-encoded version of $s (7-bit ASCII)
 */

// Note that it's *possible* that receivers will assume that the
// %-encoded string represents ISO Latin 1-encoded data; see
//
// https://bugzilla.mozilla.org/show_bug.cgi?id=18643#c8
//
// This may not happen in practice, though.  (It doesn't happen with
// Safari.)
//
// Also, for some reason Wordpress needs a utf8_uri_encode() 
// function--?  Do they actually know what they're doing?

function uri_encode($s) {
	return rawurlencode($s);
}

/**
 * Converts $s from a %-encoded string (7-bit ASCII) to the
 * corresponding binary string.  (Charset is irrelevant; the
 * conversion is binary.)
 *
 * @param $s %-encoded string (7-bit ASCII)
 * @return string
 */

function uri_decode($s) {
	return rawurldecode($s);
}

?>
