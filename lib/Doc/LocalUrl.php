<?php

// Uses a PDO-wrapped SQLite database; will be migrated to MongoDB at some point.

namespace Byron\Doc;

class LocalUrl
{
    
    protected $url;
    protected $pdo;
    
    public function __construct($url, $pdo)
    {
        $this->url = $url;
        $this->pdo = $pdo;
    }
    
    public function hits()
    {

        $sth = $this->pdo->prepare("SELECT COUNT(*) AS count FROM tracker WHERE url = ? AND gmtime > DATETIME('now', '-28 days')");
        $res = $sth->execute(array($this->url));

        $hits = $res ? $sth->fetchColumn() : 0;

        return number_format($hits);

    }

    public function sparkline($width = 140, $height = 20) {
        
//        $url = sprintf("%s%s", $this->base_url, substr(parse_url($url, PHP_URL_PATH), 1));

        $url = $this->url;
        
        $t1 = gregoriantojd(strftime("%m"), strftime("%d"), strftime("%Y")); // end date is last full day
        $t0 = $t1 - 28;
        
        $sql = "
            SELECT 
                strftime('%Y', gmtime) as year, 
                strftime('%m', gmtime) as month, 
                strftime('%d', gmtime) as day, 
                strftime('%Y-%m-%d', gmtime) as yyyymmdd, 
                count(*) AS count
            FROM tracker 
            WHERE 
                url = ? AND
                yyyymmdd >= ? AND 
                yyyymmdd < ?
            GROUP BY yyyymmdd
            ORDER BY yyyymmdd
        ";
        
        try {
            
            $sth = $this->pdo->prepare($sql);
        
            if (!$sth) {
                $s = $sql;
                $s = preg_replace("/\s+/", " ", $s);
                $s = preg_replace("/^\s+/", "", $s);
                $s = preg_replace("/\s+$/", "", $s);
                throw new \Exception("couldn't prepare statement: [$s]");
            }
            
            list($m0, $d0, $y0) = explode("/", jdtogregorian($t0));
            list($m1, $d1, $y1) = explode("/", jdtogregorian($t1));
            
            $res = $sth->execute(array(
                $url, 
                sprintf("%04d-%02d-%02d", $y0, $m0, $d0), 
                sprintf("%04d-%02d-%02d", $y1, $m1, $d1)
            ));
        
            $row = $sth->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (\Exception $e) {
            trigger_error("serious: " . $e->getMessage());
            $row = array();
        }
        
        $data = array_fill_keys(range($t0, $t1 - 1), null); // seems as though array_fill() itself should work, but that runs out of memory
        
        foreach ($row as $r) {
            $data[gregoriantojd($r["month"], $r["day"], $r["year"])] = $r["count"];
        }
        
        ksort($data);
        
        $max = max($data);
        
        if ($max < 10) { 
            $max = 10; 
        }
        
        $data = \Service_GoogleFeed::encode_simple($data, 0, $max);
        
        // Sparkline style from the sample at:
        //
        //   http://code.google.com/apis/chart/#sparkline
        
        $args = array(
            "chs" => sprintf("%dx%d", $width, $height),
            "chf" => "bg,s,e8e8e8", // background colour
            "cht" => "ls",
            "chco" => "808080",
            "chm" => "B,d0d0d0,0,0,0",
            "chls" => "1,0,0",
        //    "chxt" => "y",
        //    "chxr" => sprintf("0,0,%d", $max),
            "chd" => "s:" . join("", $data)
        );
        
        $qs = array();
        foreach ($args as $k => $v) {
            $qs[] = sprintf("%s=%s", $k, $v);
        }
        $qs = join("&", $qs);
        
        //$url = "http://chart.apis.google.com/chart?" . http_build_query($args);
        return "http://chart.apis.google.com/chart?" . $qs;
        
    }
    
    public function last_hit()
    {
        
        $url = $this->url;

        $sth = $this->pdo->prepare("SELECT strftime('%s', MAX(gmtime)) AS gmtime FROM tracker WHERE url = ?");
        $res = $sth->execute(array($url));

        if ($res && ($gmtime = $sth->fetchColumn())) {
            
            $diff = time() - $gmtime;
            
            if ($diff == 0) {
                return "0 seconds ago";
            }
            else if ($diff == 1) {
                return "1 second ago";
            }
            else if ($diff < 120) {
                return sprintf("%d seconds ago", $diff);
            }

            $diff = round($diff / 60);

            if ($diff == 0) {
                return "0 minutes ago";
            }
            else if ($diff == 1) {
                return "1 minute ago";
            }
            else if ($diff < 120) {
                return sprintf("%d minutes ago", $diff);
            }

            $diff = round($diff / 60);

            if ($diff == 0) {
                return "0 hours ago";
            }
            else if ($diff == 1) {
                return "1 hour ago";
            }
            else if ($diff < 24) {
                return sprintf("%d hours ago", $diff);
            }

            $diff = round($diff / 24);

            if ($diff == 0) {
                return "0 days ago";
            }
            else if ($diff == 1) {
                return "1 day ago";
            }
            else {
                return sprintf("%d days ago", $diff);
            }

        }
        else {
            return "not in last month";
        }
    }
    
    
}