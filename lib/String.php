<?php

namespace Byron;

class String {
    
    protected $s = null;
    
    protected $charset = null;
    
    public function __construct($s, $charset = "UTF-8")
    {
        $this->s = $s;
        $this->charset = $charset;
    }
    
    public function toNumericEntities()
    {
        $table = array(
            '&lt;'  => '&#60;', '&gt;'  => '&#62;', '&amp;' => '&#38;'
        );

        return strtr($this->toXmlEntities(), $table);
    }
    
    public function fromNumericEntities($output_charset = "UTF-8")
    {
        return html_entity_decode($this->s, ENT_QUOTES, $output_charset);
    }

    public function toXmlEntities()
    {
        return htmlentities($this->s, ENT_XML1, $this->charset);
    }

    public function fromXmlEntities($output_charset = "UTF-8")
    {
        return html_entity_decode($this->s, ENT_QUOTES, $output_charset);
    }
    
    public function toNamedEntities() 
    {
        return htmlentities($this->s, ENT_NOQUOTES, $this->charset);
    }
    
    public function fromNamedEntities($output_charset = "UTF-8")
    {
        return html_entity_decode($this->s, ENT_QUOTES, $output_charset);
    }
    
    public function toUri()
    {
        return rawurlencode($this->s);
    }
    
    public function fromUri()
    {
        return rawurldecode($this->s);
    }
    
    public function __toString()
    {
        return $this->s;
    }
    
}
