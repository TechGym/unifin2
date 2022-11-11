<?php
include "../includes/functions.php";


// Select all pending users
$sql = "SELECT user_email FROM membership WHERE status = 'pending';";
$result = $dbConnect->query($sql);
$result = $result->fetch_all(MYSQLI_ASSOC);

foreach ($result as $res) {
    $email = $res['user_email'];
    // Fetch user with email
    $sql = "SELECT * FROM users WHERE email = '$email';";
    $result = $dbConnect->query($sql);
    $loggedInfo = $result->fetch_assoc();

    include_once "../functions/index.func.php";

    $_POST['setMembership'] = true;
    if (isset($_POST["setMembership"])) {
        becomeAMember($res['user_email']);
    }
    echo "<br>";
    echo "<br>";
}
