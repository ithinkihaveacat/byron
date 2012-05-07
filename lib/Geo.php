<?php

namespace Byron;

// The functions in this file are straight PHP conversions of:
//
//   http://cpansearch.perl.org/src/JGIBSON/Geo-Ellipsoid-1.12/lib/Geo/Ellipsoid.pm
//
// The documentation is also copied from there verbatim.

class Geo {
    
    /**
     * Returns a list consisting of the number of meters per angle of 
     * latitude and longitude (measured in degrees) at the specified latitude. 
     * These values may be used for fast approximations of distance
     * calculations in the vicinity of some location.
     *
     *   list( $lat_scale, $lon_scale ) = $geo->scales($lat0);
     *   $x = $lon_scale * ($lon - $lon0); 
     *   $y = $lat_scale * ($lat - $lat0); 
     *
     * @param float $lat latitude in degrees
     * @return array latitude scale and longitude scale
     */
    
    public static function scales($lat) {
        
        // Simplified: assumes WGS84 ellipsoid, and input and
        // output in degrees.
        
        $WGS84 = array(6378137.0, 298.257223563);
        $DEGREES_PER_RADIAN = 180 / M_PI;

        $EQUATORIAL = $WGS84[0];
        $FLATTENING = 1 / $WGS84[1];
        $POLAR = $EQUATORIAL * (1.0 - $FLATTENING);

        $lat /= $DEGREES_PER_RADIAN;

        $aa = $EQUATORIAL;
        $bb = $POLAR;
        $a2 = $aa*$aa;
        $b2 = $bb*$bb;
        $d1 = $aa * cos($lat);
        $d2 = $bb * sin($lat);
        $d3 = $d1*$d1 + $d2*$d2;
        $d4 = sqrt($d3);
        $n1 = $aa * $bb;
        $latscl = ( $n1 * $n1 ) / ( $d3 * $d4 );
        $lonscl = ( $aa * $d1 ) / $d4;

        $latscl /= $DEGREES_PER_RADIAN;
        $lonscl /= $DEGREES_PER_RADIAN;

        return array($latscl, $lonscl);

    }
    
    /**
     * Returns a bounding box centered on a point given by ($lat, $lon), with a boundary of
     * $meters to the north, south, east and west.
     *
     * @param float $lat the latitude around which the bounding box will be centered
     * @param float $lon the longitude around which the bounding box will be centered
     * @param float $meters distance in meters
     * @return array ($n, $s, $e, $w) or, equivalently ($lat0, $lat1, $lon0, $lon1)
     */
    
    public static function boundingbox($lat, $lon, $meters) {

        list($latscale, $lonscale) = Geo::scales($lat);
        $latscale = 1 / $latscale; // number of degrees in a meter
        $lonscale = 1 / $lonscale; // number of degrees in a meter

        $n = $lat + ($meters * $latscale);
        $s = $lat - ($meters * $latscale);
        $e = $lon + ($meters * $lonscale);
        $w = $lon - ($meters * $lonscale);
        
        if ($e > +180) { $e = $e - 180; }
        if ($w < -180) { $w = $w + 180; }

        return array($n, $s, $e, $w);

    }
    
}
