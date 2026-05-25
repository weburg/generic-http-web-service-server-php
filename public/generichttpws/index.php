<?php
require_once "weburg/ghowst/HttpWebServiceHandler.php";
require_once "weburg/ghowst/HttpWebServiceMapper.php";

use weburg\ghowst\HttpWebServiceHandler;
use weburg\ghowst\HttpWebServiceMapper;

// Front-controller for the web service

if (str_contains($_SERVER["REQUEST_URI"], "engines")) {
    include "views/generichttpws/engines.php";
    exit();
}

include "views/generichttpws/home.php";

/*
$webService = new stdClass();

interface HttpCallback
{
    public function handleGet(): string;
}

$callback = new class implements HttpCallback {
    public function handleGet(): string {
        return 'Future GET callback';
    }
};

$mapper = new HttpWebServiceMapper($webService, "/generichttpws");
new HttpWebServiceHandler($mapper, $callback)->handleRequest();
*/
?>