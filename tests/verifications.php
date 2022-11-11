<?php
include "../includes/functions.php";
include "../functions/verifications.func.php";

// Register verification
$sql = "SELECT email_ref , email FROM verifications WHERE email_ref IS NOT NULL";
$result = $dbConnect->query($sql);
$result = $result->fetch_all(MYSQLI_ASSOC);

foreach ($result as $res) {

  $token  = matchEmailToken($res["email_ref"], $res["email"]);

  $time = gmdate("Y-m-d H:i:s");
  if (count($token) == 1) {
    // Update email_status when link is valid
    $email = $token[0]['email'];
    $sql = "UPDATE verifications SET email_status = 'verified', email_ref = NULL  WHERE email = '$email';";
    $verifications = $dbConnect->query($sql);

    // Setting a unique login key and also its syntax will be used for recovering accounts
    $code = md5($email . $time);

    $sql = "INSERT into cookies (email , cookie , timestamp ) VALUES('$email' , '$code' , '$time')";
    $cookies = $dbConnect->query($sql);
  }

  echo "<br>";
  echo "<br>";
}
