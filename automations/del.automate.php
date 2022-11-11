<?php
include "../includes/functions.php";
include "../functions/notifications.func.php";
// Delete details of a registered after two hours of registration if they don't verify their account
/*
1. Fetch all unverified users and their registration time
2. Fetch current time and subtract the registration time + 2 hours from it
3. Delete user if the result is positive
*/
function fetchUnverifiedUsers()
{
  global $dbConnect;
  $sql = "SELECT email FROM verifications WHERE email_status ='unverified'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function fetchRegistrationDate($email)
{
  global $dbConnect;
  $sql = "SELECT joined_date FROM users WHERE email ='$email'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  return $result;
}
function deleteUnverifiedUser($email)
{
  global $dbConnect;
  $sql = "DELETE FROM users WHERE email ='$email'";
  $dbConnect->query($sql);
  $sql = "DELETE FROM verifications WHERE email ='$email'";
  $dbConnect->query($sql);
  $sql = "DELETE FROM membership WHERE user_email ='$email'";
  $dbConnect->query($sql);
}

$unverifiedUsers = fetchUnverifiedUsers();
foreach ($unverifiedUsers as $unverifiedUser) {
  $joined_date = fetchRegistrationDate($unverifiedUser['email'])['joined_date'];
  $currentTime = gmdate("Y-m-d H:i:s");
  $differences = strtotime($currentTime) - (strtotime($joined_date) + 3600 * 2);
  if ($differences > 0) {
    deleteUnverifiedUser($unverifiedUser['email']);
  }
}
