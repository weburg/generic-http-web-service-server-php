<?php
$pageTitle = "Home";
?>

<?php include "views/header.php" ?>

<p>The time is <?= time() ?>.</p>

<p><?= $nameFun ?></p>

<p>Request URI: <?= $_SERVER["REQUEST_URI"] ?></p>

<?php include "views/footer.php" ?>