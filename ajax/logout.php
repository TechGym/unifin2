<?php
include "../includes/functions.php";
if (isset($_POST['logout'])) {
  $code = generateKey();
  $email = $loggedInfo['email'];
  $sql = "UPDATE cookies SET cookie = '$code' WHERE email = '$email'";
  $res = $dbConnect->query($sql);
  if ($res) {
    // Clear all sessions and cookies
    setcookie(md5('logUser'), "", time() - (24 * 3600), "/");
    unset($_SESSION);
    session_destroy();
    echo "success";
  }
}
