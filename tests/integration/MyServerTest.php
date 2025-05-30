<?php
namespace integration;

use PHPUnit\Framework\TestCase;

class MyServerTest extends TestCase {
    public function testServer() {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_URL, "http://localhost/generichttpws/");

        $result = curl_exec($handle);

        $statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        $this->assertEquals(200, $statusCode);
        $this->assertStringContainsString("Service description here.", $result);
    }
}
?>