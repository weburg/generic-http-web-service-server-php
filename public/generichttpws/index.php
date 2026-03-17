<?php
// Front-controller for the web service

if (str_contains($_SERVER["REQUEST_URI"], "engines")) {
    include "views/generichttpws/engines.php";
    exit();
}

include "views/generichttpws/home.php";
?>