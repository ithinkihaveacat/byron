<?php

namespace Local {

    class Foo { }
    class Bar extends Foo {}

}

namespace {

    class DynamicTest extends PHPUnit_Framework_Testcase
    {
        public function setUp()
        {
            // No autoloader for functions, so we have to do it manually
            blib("dynamic");
            dreset();
        }

        public function testDpush1()
        {
            dpush("foo", "bar");
            $this->assertEquals("bar", dget("foo"));
            $this->assertEquals("bar", dget("foo"));
            $this->assertEquals("bar", dget("foo"));
        }

        public function testDpush_PushStack_()
        {
            dpush("foo", "a");
            $this->assertEquals("a", dget("foo"));
            dpush("foo", "b");
            $this->assertEquals("b", dget("foo"));
            dpush("foo", "c");
            $this->assertEquals("c", dget("foo"));
            dpop("foo");
            $this->assertEquals("b", dget("foo"));
            dpop("foo");
            $this->assertEquals("a", dget("foo"));
        }

        public function testDcall_SingleScope_PushedValue()
        {
            dpush("foo", "baz");
            $this->assertEquals("baz", dget("foo"));
            $ret = dcall(
                array("foo" => "bar"),
                function() {
                    return dget("foo");
                }
            );
            $this->assertEquals("bar", $ret);
            $this->assertEquals("baz", dget("foo"));
        }

        public function testDnew_PushClass_InstanceOfPushedClass()
        {
            dpush('Local\Foo', 'Local\Foo');
            $foo = dnew('Local\Foo');
            $this->assertInstanceOf("Local\Foo", $foo);
        }

        public function testDnew_PopClass_InstanceOfOriginalClass()
        {
            dpush('Local\Foo', 'Local\Foo');
            dpush('Local\Foo', 'Local\Bar');
            $foo = dnew('Local\Foo');
            $this->assertInstanceOf('Local\Foo', $foo);
            $this->assertInstanceOf('Local\Bar', $foo);
            dpop('Local\Foo');
            $foo = dnew('Local\Foo');
            $this->assertInstanceOf('Local\Foo', $foo);
            $this->assertNotInstanceOf('Local\Bar', $foo);
        }
    }

}
