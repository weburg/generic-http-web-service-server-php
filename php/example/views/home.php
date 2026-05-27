<?php
require_once "arg.php";

$date = arg("date", new DateTime("2024/02/02T01:01", new DateTimeZone("UTC")));
$requestUri = arg("");
$pageTitle = "Home";
?>

<?php include "example/views/header.php" ?>

<h2>GHoWSt DUETS</h2>

<img src="/images/ghowstduetslogo.png" alt="GHoWSt DUETS logo" width="744" height="682" style="width: auto; height: 72px; float: left; margin-right: 10px; margin-bottom: 10px;">

<h3>Depend Upon Existing Technology Stack</h3>

<p>The time is <?= $date->format('Y-m-d\TH:i:s.v\Z') ?>. <?= myFunction("PHP") ?>.</p>

<p>Request URI: <?= $requestUri ?></p>

<?php include "example/views/footer.php" ?>