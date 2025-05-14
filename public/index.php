<?php
require_once "myFunction.php";

// This is a simple front-controller for the root of the site

// Do any routing or dynamic stuff here
$nameFun = myFunction("Bobcat");

include "views/home.php";
?>