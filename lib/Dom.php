<?php

namespace Byron;

class Dom {
    
    /**
     * Takes the filename of a HTML file, returns a proxied DOMDocument object that
     * does everything a DOMDocument does, but with some extra convenience functions.
     *
     * @param string $filename
     * @return \Byron\Dom\DOMDocument Does everything a DOMDocument does, but has additional helper functions
     */

    static function loadHtmlFile($filename) {
        $doc = new \DOMDocument();
        $doc->loadHTMLFile($filename);
        return new Dom\DOMDocument($doc);
    }

    /**
     * Takes an HTML string, returns a proxied DOMDocument object that
     * does everything a DOMDocument does, but with some extra convenience functions.
     *
     * @param string $s XML string
     * @return \Byron\Dom\DOMDocument Does everything a DOMDocument does, but has additional helper functions
     */

    static function loadHtml($s) {
        $doc = new \DOMDocument();
        $doc->loadHTML($s);
        return new Dom\DOMDocument($doc);
    }

    /**
     * Takes the filename of an XML file, returns a proxied DOMDocument object that
     * does everything a DOMDocument does, but with some extra convenience functions.
     *
     * (This is equivalent to load() in the DOMDocument API, but is renamed here for
     * consistency.)
     *
     * @param string $filename
     * @return \Byron\Dom\DOMDocument Does everything a DOMDocument does, but has additional helper functions
     */

    static function loadXmlFile($filename) {
        $doc = new \DOMDocument();
        $res = $doc->load($filename);
        if (!$res) {
            throw new \Exception("can't load [$filename]");
        }
        return new Dom\DOMDocument($doc);
    }

    /**
     * Takes an XML string, returns a proxied DOMDocument object that
     * does everything a DOMDocument does, but with some extra convenience functions.
     *
     * @param string $s XML string
     * @return \Byron\Dom\DOMDocument Does everything a DOMDocument does, but has additional helper functions
     */

    static function loadXml($s) {
        $doc = new \DOMDocument();
        $doc->loadXML($s);
        return new Dom\DOMDocument($doc);
    }

}