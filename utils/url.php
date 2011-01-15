<?php

// Takes a hash, $args, and returns a query string corresponding to the hash.
//
// Example:
//
//   url_make_qs(array("name" => "Michael", "email" => "michael.stillwell@dcinteract.com"));
//
// returns
//
//   "name=Michael&email=michael.stillwell@dcinteract.com"

function url_build_qs($args) {
    return http_build_query($args);
}

// Splits a query string (uses $_SERVER["QUERY_STRING"] if $qs is null) into an array;
// the inverse of url_make_qs.

function url_split_qs($qs = null) {
    if (is_null($qs)) {
        $qs = array_key_exists('QUERY_STRING', $_SERVER) ? $_SERVER['QUERY_STRING'] : '';
    }
    $a = array();
    parse_str($qs, $a);
    return $a;
}

// "Updates" query string with the values from $args.  Use this if you want
// to change some of the values in a query string, but leave the others
// untouched.  (Useful on search result pages where you have a search
// embedded into a query, and you need to make some change to the query
// to e.g. change the sort order.)

function url_update_qs($args, $qs = null) {
	$a = url_split_qs($qs);
	foreach ($args as $k => $v) {
		$a[$k] = $v;
	}
	return url_build_qs($a);
}

// Converts an arbitrary string, $s, into something that looks like a
// filename: punctuation is stripped, spaces are converted to '-',
// and so forth.  The maximum length of the string is controlled by
// $NAME_MAX_LENGTH, though you will always get at least one "word".
// (i.e. if the length of the first word in $s is greater than
// $NAME_MAX_LENGTH, you get that word back.)

function url_make_name($s) {

	$NAME_MAX_LENGTH = 30;

	$s = preg_replace("/'/", "", $s);
	$s = preg_replace("/\W+/", " ", $s);
	$s = trim(strtolower($s));
	
	$parts = preg_split("/\W+/", $s);
	
	$s = array_shift($parts);
	foreach ($parts as $p) {
		if (strlen($s) + strlen($p) > $NAME_MAX_LENGTH) {
			break;
		}
		else {
			$s .= "-" . $p;
		}
	}
	return $s;

}

// Returns url of current page, include query string

function url_self() {
	return $_SERVER['REQUEST_URI']; // SCRIPT_NAME is real script name under mod_redirect
}

// Like url_self(), except that it strip any query string that might exist off the end.

function url_self_no_qs() {
	return substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"] . "?", "?"));
}
