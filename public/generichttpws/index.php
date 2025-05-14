<?php
// Front-controller for the web service

if (str_contains($_SERVER["REQUEST_URI"], "engines")) {
    include "views/engines.php";
    exit();
}

include "views/generichttpws/home.php";
?>