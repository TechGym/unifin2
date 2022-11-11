<?php
function matchEmailToken($token, $email) {
  global $dbConnect;
  $sql = "SELECT email , email_status FROM verifications WHERE email_ref = '$token' OR email = '$email';";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function fetchEmailStatus($email) {
  global $dbConnect;
  $sql = "SELECT email_status FROM verifications WHERE email = '$email'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result[0]['email_status'];
}
