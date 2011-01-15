<?php

/**
 * Enhances PDO in a few (minor) ways:
 * 
 *  * The default $driver_options set PDO::ATTR_PERSISTENT to true
 *    so that database connections are re-used.
 *  * A few convenience SQL-generation methods are added.
 * 
 * The general philosphy with this is: don't worry about writing some
 * code to generate the SQL for you: bust out raw SQL early and
 * often.
 * 
 * (Because: raw SQL is comprehensible (non-magic); it's easily
 * debugged (since it can be cut-and-pasted to and from a query
 * browser); it avoids having to learn another language (the
 * language that maps from language-specific constructs to SQL); and
 * at some point you'll have to write raw SQL anyway (since, unless
 * you do a LOT of work, you aren't going to be able to reproduce
 * some fancy SQL with your query language).
 * 
 * Note that there's very little difference between caching the PDO
 * connection yourself, and getting PDO to do it (for SQLite at
 * least)--see
 * 
 * http://netevil.org/blog/2005/sep/benchmarking-in-general
 * 
 * TODO write execute() method, and get it to handle SQLITE_BUSY
 * errors better.  See
 * 
 * http://www.sqlite.org/c3ref/busy_handler.html
 * http://www.sqlite.org/c3ref/c_abort.html
 * 
 * $error = $sth->errorInfo();
 * $error[1] == 5
 * 
 */
 
namespace Byron;

class PDO extends \PDO {
    
    function __construct($dsn, $username = null, $password = null, $driver_options = array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_TIMEOUT => 1)) {
        parent::__construct($dsn, $username, $password, $driver_options);
    }
    
    function insert_array($table, $row) {
    
        $s1 = join(", ", array_keys($row));
        $s2 = join(", ", array_fill(0, count($row), "?"));
        
        $sql = "INSERT INTO $table($s1) VALUES($s2)";
        $arg = array_values($row);
        
        return array($sql, $arg);
        
    }
    
    function update_array($table, $row, $id = "id") {
    
        $arg = array();
        
        $s = array();
        foreach ($row as $k => $v) {
            if ($k != $id) { 
                $s[] = "$k = ?";
                $arg[] = $v;
            }
        }
        $s = join(", ", $s);
        
        $sql = "UPDATE $table SET $s WHERE $id = ?";
        $arg[] = $row[$id];
        
        return array($sql, $arg);

    }
    
    function delete_array($table, $row = array(), $cmp = "=") {
    
        $s = array();
        foreach (array_keys($row) as $k) {
            $s[] = "$k $cmp ?";
        }
        $s = join(" AND ", $s);
        
        if (strlen($s) > 0) {
            $sql = "DELETE FROM $table WHERE $s";
            return array($sql, array_values($row));
        }
        else {
            $sql = "DELETE FROM $table";
            return array($sql, array());
        }

    }
    
    function select_array($table, $row = array(), $cmp = "=", $orderby = null) {
    
        $orderby = empty($orderby) ? "" : "ORDER BY $orderby";
        
        $s = array();
        foreach (array_keys($row) as $k) {
            $s[] = "$k $cmp ?";
        }
        $s = join(" AND ", $s);

        if (strlen($s) > 0) {
            $sql = "SELECT * FROM $table WHERE $s $orderby";
            return array($sql, array_values($row));
        }
        else {
            $sql = "SELECT * FROM $table $orderby";
            return array($sql, array());
        }

    }
    
}
