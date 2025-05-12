<?php
require_once "myFunction.php";

if (str_contains($_SERVER["REQUEST_URI"], "engines")) {
    include "views/engines.php";
    exit();
}
?>

<?php include "views/header.php" ?>

<h1>Home</h1>

<p>The time is <?= time() ?>.</p>

<p><?= myFunction("Bobcat") ?></p>

<p>Request URI: <?= $_SERVER["REQUEST_URI"] ?></p>

<p>Path info: <?= $_SERVER["PATH_INFO"] ?></p>

<?php include "views/footer.php" ?>