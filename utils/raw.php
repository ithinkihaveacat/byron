<?php

/**
 * Similar to print_r(), except that it writes to /tmp/rawlog.log.  This function is
 * intended for debugging, where you want to be absolutely sure that your output
 * is not being captured or buffered or otherwise interfered with.
 */

function rawlog() {
    
    $s = "";
    for ($i = 0; $i < func_num_args(); $i++) {
        $arg = func_get_arg($i);
        if (is_numeric($arg) || is_string($arg)) {
            $s .= $arg;
        }
        else {
            $s .= var_export($arg, true);
        }
    }
    if (empty($s)) $s = "[EMPTY]";
    
    $s = trim($s) . "\n";
    
    // TODO Investigate logging to a FIFO (mkfifo /tmp/foo)--
    // file needs to be opened in non-blocking mode for this to
    // work.

    file_put_contents("/tmp/rawlog.log", $s, FILE_APPEND | LOCK_EX);

}

/**
 * Writes a stack trace to /tmp/rawlog.log.
 */

function rawstack() {

    $a = array();

    foreach (debug_backtrace() as $s) {
        foreach (array("file", "line", "function") as $k) {
            if (!array_key_exists($k, $s)) {
                $s[$k] = "<UNKNOWN>";
            }
        }
        $a[] = array(
            "file" => $s["file"],
            "line" => $s["line"],
            "function" => $s["function"]
        );
    }

    rawlog($a);
}
