<?php
include_once "includes/functions.php";
statusRedirect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache , no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>UNIFIN - Map</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/styles.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
    <?php include "includes/notifications.php" ?>
    <?php include "includes/header.php" ?>
    <?php include "includes/menu.php" ?>


    <script src="./js/map.js" type="module" defer></script>
</body>

</html>