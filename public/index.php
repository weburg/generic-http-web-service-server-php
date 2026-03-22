<?php
require_once "myFunction.php";

// This is a simple front-controller for the root of the site

// Do any routing or dynamic stuff here
$date = new DateTime("now", new DateTimeZone("UTC"));
$requestUri = $_SERVER["REQUEST_URI"];
include "views/home.php";
?>