<?php
require_once "MyClass.php";

use PHPUnit\Framework\TestCase;

class MyClassTest extends TestCase
{
    public function testGiveWhat()
    {
        $myClass = new MyClass();
        $this->assertEquals("What", $myClass->giveWhat());
    }
}
?>