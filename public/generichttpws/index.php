<?php
require_once "example/domain/Omnibus.php";
require_once "weburg/ghowst/HttpWebServiceHandler.php";
require_once "weburg/ghowst/HttpWebServiceMapper.php";

use example\domain\Omnibus;
use weburg\ghowst\HttpWebServiceHandler;
use weburg\ghowst\HttpWebServiceMapper;

// Front-controller for the web service

if (str_contains($_SERVER["REQUEST_URI"], "engines")) {
    include "views/generichttpws/engines.php";
    exit();
}

if (str_contains($_SERVER["REQUEST_URI"], "omnibus")) {
    $omnibus = new Omnibus();
    $omnibus->birthtime = new DateTime("2016-05-11T12:00:00.000");
    $omnibus->sendtime = new DateTime();
    $omnibus->toppings = ["Cheese", "Pepperoni", "Sausage"];
    $omnibus->sides = ["Fries", "Onion Rings"];
    $omnibus->onFire = false;
    $omnibus->document = null;
    $omnibus->pairing = [
        "Steak" => "Cabernet Sauvignon",
        "Fish" => "Chardonnay",
    ];

    echo json_encode($omnibus);

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