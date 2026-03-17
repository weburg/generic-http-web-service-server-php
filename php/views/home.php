<?php
$pageTitle = "Home";
$date = new DateTime();
?>

<?php include "views/header.php" ?>

<h2>GHoWSt DUETS</h2>

<img src="/images/ghowstduetlogo.png" alt="GHoWSt DUET logo" width="744" height="682" style="width: auto; height: 72px; float: left; margin-right: 10px; margin-bottom: 10px;">

<h3>Depend Upon Existing Technology Stack</h3>

<p>The time is <?= $date->format(DateTime::ISO8601_EXPANDED) ?>. <?= $nameFun ?>.</p>

<p>Request URI: <?= $_SERVER["REQUEST_URI"] ?></p>

<?php include "views/footer.php" ?>