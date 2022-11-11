<?php
include "includes/functions.php";
include "functions/verifications.func.php";
/*
If user is logged in,  user can't access the verifyemail on registration page 
but can access editEmail and verifyPhone pages
*/
if (isset($loggedInfo) && $_GET["type"] != "verifyphone" && $_GET["type"] != "editEmail") {
    header("Location: ./");
}
/*
If user is not logged in,  user can't access the verifyphone and editEmail pages
but can access verifyemail
*/
if (!isset($loggedInfo) && $_GET["type"] == "verifyphone" && $_GET["type"] == "editEmail") {
    header("Location: ./");
}
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
    <title>UNIFIN - Validations</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/verifications.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
    <div class="container">
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
            <h1>UNIFIN</h1>
        </div>
        <?php
        /* 
        If it is not set $_GET['type'] , user will be redirected to profile page if logged in 
        and login page if not logged in else can proceed to verifyemail ,  verifyphone or editEmail
        */
        ?>
        <?php if (isset($_GET["type"])) {
            $arr = ['verifyemail', "verifyphone", 'editEmail'];
            include "includes/verifyemail.php";
            include "includes/verifyphone.php";
            if (!in_array($_GET['type'], $arr)) {
                // 404 page not found
                if (isset($loggedInfo)) {
                    header("Location: ./profile");
                } else {
                    header("Location: ./login");
                }
            }
        } else {
            if (isset($loggedInfo)) {
                header("Location: ./profile");
            } else {
                header("Location: ./login");
            }
        } ?>
    </div>

    <script src="./js/verifications.js" type="module" defer></script>
</body>



</html>