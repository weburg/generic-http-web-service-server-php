<?php
$pageTitle = "Home";
?>

<?php include "views/header.php" ?>

<h2>GHoWSt DUETS</h2>

<img src="/images/ghowstduetlogo.png" alt="GHoWSt DUET logo" width="744" height="682" style="width: auto; height: 72px; float: left; margin-right: 10px; margin-bottom: 10px;">

<h3>Depend Upon Existing Technology Stack</h3>

<p>The time is <?= $date->format(DateTime::ISO8601_EXPANDED) ?>. <?= myFunction("PHP") ?>.</p>

<p>Request URI: <?= $requestUri ?></p>

<?php include "views/footer.php" ?>