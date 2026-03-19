<?php
require_once "myFunction.php";

use PHPUnit\Framework\TestCase;

class myFunctionTest extends TestCase {
    public function testMyFunction() {
        $this->assertStringContainsString("PHP", myFunction("PHP"));
    }
}
?>