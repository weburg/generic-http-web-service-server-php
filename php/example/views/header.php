<?php
require_once "arg.php";

$pageTitle = arg("pageTitle");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="/styles/main.css">
</head>
<body>

<header>
    <h1><?= $pageTitle ?></h1>
</header>

<main>