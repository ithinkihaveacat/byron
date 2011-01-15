<?php

$text = <<<END
Once I started doing felonies, it became less hard. No matter how liberal I
am, I’m still outraged by crimes of violence. Regardless of whether I can
sympathize with the causes that lead these individuals to do these crimes,
the effects are outrageous.  -- Sonia Sotomayor
END;

class YahooTest extends PHPUnit_Framework_Testcase {
    
    public function testTermExtraction() {
        
        global $text;
        
        $yahoo = $this->getBroker()->getYahooService();
        
        $tag = $yahoo->termExtraction($text);

        $this->assertContains("crimes", $tag);
        $this->assertNotContains("banana", $tag);
        
    }
    
}
