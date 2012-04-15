<?php

class GeoTest extends PHPUnit_Framework_Testcase {
    
    public function testBoundingBox() {
        
        list($n, $s, $e, $w) = \Byron\Geo::boundingbox(0, 0, 50000);
        
        $this->assertGreaterThan(0, $n);
        $this->assertLessThan(0, $s);
        $this->assertGreaterThan(0, $e);
        $this->assertLessThan(0, $w);
        
        list($n, $s, $e, $w) = \Byron\Geo::boundingbox(0, 180, 50000);
        
        $this->assertGreaterThan(0, $n);
        $this->assertLessThan(0, $s);
        
        $this->assertGreaterThan(-180, $e);
        $this->assertLessThan(180, $e);
        
        $this->assertGreaterThan(-180, $w);
        $this->assertLessThan(180, $w);
        
        list($n, $s, $e, $w) = \Byron\Geo::boundingbox(0, -180, 50000);
        
        $this->assertGreaterThan(0, $n);
        $this->assertLessThan(0, $s);
        
        $this->assertGreaterThan(-180, $e);
        $this->assertLessThan(180, $e);
        
        $this->assertGreaterThan(-180, $w);
        $this->assertLessThan(180, $w);
        
    }
    
    public function testScales() {
        
        // Tests from:
        //
        //   http://cpansearch.perl.org/src/JGIBSON/Geo-Ellipsoid-1.12/t/04-scale.t
        
        list( $ys, $xs ) = \Byron\Geo::scales(0);
        $this->assertEquals( $xs, 111319.490793274, "", 1e-6);
        $this->assertEquals( $ys, 110574.275821594, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(1);
        $this->assertEquals( $xs, 111302.649769732, "", 1e-6);
        $this->assertEquals( $ys, 110574.614016816, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(2);
        $this->assertEquals( $xs, 111252.131520103, "", 1e-6);
        $this->assertEquals( $ys, 110575.628200778, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(3);
        $this->assertEquals( $xs, 111167.950506731, "", 1e-6);
        $this->assertEquals( $ys, 110577.317168814, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(4);
        $this->assertEquals( $xs, 111050.130831399, "", 1e-6);
        $this->assertEquals( $ys, 110579.678914611, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(5);
        $this->assertEquals( $xs, 110898.706232127, "", 1e-6);
        $this->assertEquals( $ys, 110582.710632409, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(6);
        $this->assertEquals( $xs, 110713.720078689, "", 1e-6);
        $this->assertEquals( $ys, 110586.408720072, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(7);
        $this->assertEquals( $xs, 110495.225366811, "", 1e-6);
        $this->assertEquals( $ys, 110590.768783042, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(8);
        $this->assertEquals( $xs, 110243.284711052, "", 1e-6);
        $this->assertEquals( $ys, 110595.785639154, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(9);
        $this->assertEquals( $xs, 109957.970336344, "", 1e-6);
        $this->assertEquals( $ys, 110601.453324332, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(10);
        $this->assertEquals( $xs, 109639.364068153, "", 1e-6);
        $this->assertEquals( $ys, 110607.765099137, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(11);
        $this->assertEquals( $xs, 109287.557321245, "", 1e-6);
        $this->assertEquals( $ys, 110614.713456187, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(12);
        $this->assertEquals( $xs, 108902.651087025, "", 1e-6);
        $this->assertEquals( $ys, 110622.290128422, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(13);
        $this->assertEquals( $xs, 108484.755919402, "", 1e-6);
        $this->assertEquals( $ys, 110630.486098225, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(14);
        $this->assertEquals( $xs, 108033.991919153, "", 1e-6);
        $this->assertEquals( $ys, 110639.291607378, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(15);
        $this->assertEquals( $xs, 107550.488716736, "", 1e-6);
        $this->assertEquals( $ys, 110648.696167862, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(16);
        $this->assertEquals( $xs, 107034.385453513, "", 1e-6);
        $this->assertEquals( $ys, 110658.688573475, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(17);
        $this->assertEquals( $xs, 106485.830761325, "", 1e-6);
        $this->assertEquals( $ys, 110669.256912276, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(18);
        $this->assertEquals( $xs, 105904.982740377, "", 1e-6);
        $this->assertEquals( $ys, 110680.388579831, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(19);
        $this->assertEquals( $xs, 105292.008935377, "", 1e-6);
        $this->assertEquals( $ys, 110692.070293263, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(20);
        $this->assertEquals( $xs, 104647.086309862, "", 1e-6);
        $this->assertEquals( $ys, 110704.288106085, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(21);
        $this->assertEquals( $xs, 103970.401218673, "", 1e-6);
        $this->assertEquals( $ys, 110717.027423818, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(22);
        $this->assertEquals( $xs, 103262.149378494, "", 1e-6);
        $this->assertEquals( $ys, 110730.273020361, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(23);
        $this->assertEquals( $xs, 102522.535836412, "", 1e-6);
        $this->assertEquals( $ys, 110744.00905512, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(24);
        $this->assertEquals( $xs, 101751.774936417, "", 1e-6);
        $this->assertEquals( $ys, 110758.21909087, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(25);
        $this->assertEquals( $xs, 100950.090283789, "", 1e-6);
        $this->assertEquals( $ys, 110772.88611234, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(26);
        $this->assertEquals( $xs, 100117.714707292, "", 1e-6);
        $this->assertEquals( $ys, 110787.992545504, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(27);
        $this->assertEquals( $xs, 99254.890219118, "", 1e-6);
        $this->assertEquals( $ys, 110803.520277558, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(28);
        $this->assertEquals( $xs, 98361.8679724994, "", 1e-6);
        $this->assertEquals( $ys, 110819.450677574, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(29);
        $this->assertEquals( $xs, 97438.9082169266, "", 1e-6);
        $this->assertEquals( $ys, 110835.764617804, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(30);
        $this->assertEquals( $xs, 96486.2802508965, "", 1e-6);
        $this->assertEquals( $ys, 110852.442495617, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(31);
        $this->assertEquals( $xs, 95504.2623721221, "", 1e-6);
        $this->assertEquals( $ys, 110869.464256056, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(32);
        $this->assertEquals( $xs, 94493.1418251297, "", 1e-6);
        $this->assertEquals( $ys, 110886.809414981, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(33);
        $this->assertEquals( $xs, 93453.2147461739, "", 1e-6);
        $this->assertEquals( $ys, 110904.457082788, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(34);
        $this->assertEquals( $xs, 92384.7861053995, "", 1e-6);
        $this->assertEquals( $ys, 110922.385988675, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(35);
        $this->assertEquals( $xs, 91288.1696461796, "", 1e-6);
        $this->assertEquals( $ys, 110940.574505431, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(36);
        $this->assertEquals( $xs, 90163.6878215616, "", 1e-6);
        $this->assertEquals( $ys, 110959.000674728, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(37);
        $this->assertEquals( $xs, 89011.6717277532, "", 1e-6);
        $this->assertEquals( $ys, 110977.642232884, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(38);
        $this->assertEquals( $xs, 87832.461034582, "", 1e-6);
        $this->assertEquals( $ys, 110996.476637075, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(39);
        $this->assertEquals( $xs, 86626.4039128637, "", 1e-6);
        $this->assertEquals( $ys, 111015.481091969, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(40);
        $this->assertEquals( $xs, 85393.8569586184, "", 1e-6);
        $this->assertEquals( $ys, 111034.632576751, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(41);
        $this->assertEquals( $xs, 84135.1851140718, "", 1e-6);
        $this->assertEquals( $ys, 111053.907872507, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(42);
        $this->assertEquals( $xs, 82850.7615853864, "", 1e-6);
        $this->assertEquals( $ys, 111073.283589948, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(43);
        $this->assertEquals( $xs, 81540.9677570662, "", 1e-6);
        $this->assertEquals( $ys, 111092.736197432, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(44);
        $this->assertEquals( $xs, 80206.1931029833, "", 1e-6);
        $this->assertEquals( $ys, 111112.242049253, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(45);
        $this->assertEquals( $xs, 78846.8350939781, "", 1e-6);
        $this->assertEquals( $ys, 111131.777414176, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(46);
        $this->assertEquals( $xs, 77463.2991019873, "", 1e-6);
        $this->assertEquals( $ys, 111151.318504168, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(47);
        $this->assertEquals( $xs, 76055.9983006586, "", 1e-6);
        $this->assertEquals( $ys, 111170.841503309, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(48);
        $this->assertEquals( $xs, 74625.3535624143, "", 1e-6);
        $this->assertEquals( $ys, 111190.322596824, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(49);
        $this->assertEquals( $xs, 73171.7933519306, "", 1e-6);
        $this->assertEquals( $ys, 111209.738000236, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(50);
        $this->assertEquals( $xs, 71695.753616003, "", 1e-6);
        $this->assertEquals( $ys, 111229.063988562, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(51);
        $this->assertEquals( $xs, 70197.6776697733, "", 1e-6);
        $this->assertEquals( $ys, 111248.276925556, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(52);
        $this->assertEquals( $xs, 68678.0160792985, "", 1e-6);
        $this->assertEquals( $ys, 111267.353292927, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(53);
        $this->assertEquals( $xs, 67137.2265404469, "", 1e-6);
        $this->assertEquals( $ys, 111286.269719523, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(54);
        $this->assertEquals( $xs, 65575.7737541096, "", 1e-6);
        $this->assertEquals( $ys, 111305.003010423, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(55);
        $this->assertEquals( $xs, 63994.1292977257, "", 1e-6);
        $this->assertEquals( $ys, 111323.530175906, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(56);
        $this->assertEquals( $xs, 62392.7714931183, "", 1e-6);
        $this->assertEquals( $ys, 111341.828460265, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(57);
        $this->assertEquals( $xs, 60772.1852706498, "", 1e-6);
        $this->assertEquals( $ys, 111359.875370412, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(58);
        $this->assertEquals( $xs, 59132.8620297075, "", 1e-6);
        $this->assertEquals( $ys, 111377.64870425, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(59);
        $this->assertEquals( $xs, 57475.2994955351, "", 1e-6);
        $this->assertEquals( $ys, 111395.12657876, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(60);
        $this->assertEquals( $xs, 55800.0015724362, "", 1e-6);
        $this->assertEquals( $ys, 111412.287457779, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(61);
        $this->assertEquals( $xs, 54107.4781933752, "", 1e-6);
        $this->assertEquals( $ys, 111429.110179413, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(62);
        $this->assertEquals( $xs, 52398.2451660134, "", 1e-6);
        $this->assertEquals( $ys, 111445.573983052, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(63);
        $this->assertEquals( $xs, 50672.8240152185, "", 1e-6);
        $this->assertEquals( $ys, 111461.65853596, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(64);
        $this->assertEquals( $xs, 48931.7418220956, "", 1e-6);
        $this->assertEquals( $ys, 111477.343959384, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(65);
        $this->assertEquals( $xs, 47175.5310595919, "", 1e-6);
        $this->assertEquals( $ys, 111492.610854148, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(66);
        $this->assertEquals( $xs, 45404.7294247327, "", 1e-6);
        $this->assertEquals( $ys, 111507.440325702, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(67);
        $this->assertEquals( $xs, 43619.8796675553, "", 1e-6);
        $this->assertEquals( $ys, 111521.814008585, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(68);
        $this->assertEquals( $xs, 41821.5294168082, "", 1e-6);
        $this->assertEquals( $ys, 111535.714090256, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(69);
        $this->assertEquals( $xs, 40010.2310024944, "", 1e-6);
        $this->assertEquals( $ys, 111549.12333427, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(70);
        $this->assertEquals( $xs, 38186.5412753387, "", 1e-6);
        $this->assertEquals( $ys, 111562.025102756, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(71);
        $this->assertEquals( $xs, 36351.0214232683, "", 1e-6);
        $this->assertEquals( $ys, 111574.403378166, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(72);
        $this->assertEquals( $xs, 34504.2367849983, "", 1e-6);
        $this->assertEquals( $ys, 111586.242784253, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(73);
        $this->assertEquals( $xs, 32646.7566608212, "", 1e-6);
        $this->assertEquals( $ys, 111597.52860626, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(74);
        $this->assertEquals( $xs, 30779.1541207048, "", 1e-6);
        $this->assertEquals( $ys, 111608.246810274, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(75);
        $this->assertEquals( $xs, 28902.0058098066, "", 1e-6);
        $this->assertEquals( $ys, 111618.38406172, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(76);
        $this->assertEquals( $xs, 27015.8917515192, "", 1e-6);
        $this->assertEquals( $ys, 111627.927742966, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(77);
        $this->assertEquals( $xs, 25121.3951481649, "", 1e-6);
        $this->assertEquals( $ys, 111636.865970013, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(78);
        $this->assertEquals( $xs, 23219.1021794639, "", 1e-6);
        $this->assertEquals( $ys, 111645.187608236, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(79);
        $this->assertEquals( $xs, 21309.6017989022, "", 1e-6);
        $this->assertEquals( $ys, 111652.882287157, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(80);
        $this->assertEquals( $xs, 19393.4855281322, "", 1e-6);
        $this->assertEquals( $ys, 111659.940414223, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(81);
        $this->assertEquals( $xs, 17471.3472495414, "", 1e-6);
        $this->assertEquals( $ys, 111666.35318757, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(82);
        $this->assertEquals( $xs, 15543.7829971289, "", 1e-6);
        $this->assertEquals( $ys, 111672.112607742, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(83);
        $this->assertEquals( $xs, 13611.3907458309, "", 1e-6);
        $this->assertEquals( $ys, 111677.211488361, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(84);
        $this->assertEquals( $xs, 11674.7701994437, "", 1e-6);
        $this->assertEquals( $ys, 111681.64346572, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(85);
        $this->assertEquals( $xs, 9734.52257729095, "", 1e-6);
        $this->assertEquals( $ys, 111685.403007281, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(86);
        $this->assertEquals( $xs, 7791.25039978636, "", 1e-6);
        $this->assertEquals( $ys, 111688.485419075, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(87);
        $this->assertEquals( $xs, 5845.55727304685, "", 1e-6);
        $this->assertEquals( $ys, 111690.886851982, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(88);
        $this->assertEquals( $xs, 3898.04767271025, "", 1e-6);
        $this->assertEquals( $ys, 111692.604306881, "", 1e-6);

        list( $ys, $xs ) = \Byron\Geo::scales(89);
        $this->assertEquals( $xs, 1949.32672711493, "", 1e-6);
        $this->assertEquals( $ys, 111693.635638667, "", 1e-6);
        
    }
    
}
