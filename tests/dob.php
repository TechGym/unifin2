<?php

// Fetch all users with 0000-00-00 dob
include "../includes/functions.php";

if (isset($dob)) {
  $sql = "SELECT dob , user_id FROM users WHERE dob='0000-00-00' ";
  $res = $dbConnect->query($sql);

  $res = $res->fetch_all(MYSQLI_ASSOC);

  $today = strtotime(gmdate("Y-m-d")) - 3600 * 24 * 10 * 365;
  $upper = 249647200;


  foreach ($res as $e) {
    $rand = rand($upper, $today);
    $userId = $e['user_id'];
    $dob = date("Y-m-d", $rand);
    $sql = "UPDATE users SET dob = '$dob' WHERE user_id='$userId'";
    $dbConnect->query($sql);
    echo "success";
    echo "<br>";
    echo "<br>";
  }
}

$gender = true;
if (isset($gender)) {
  $titles = ['Mr.', "Miss", "Mrs."];
  $sql = "SELECT user_id FROM users WHERE title is NULL ";
  $res = $dbConnect->query($sql);

  $res = $res->fetch_all(MYSQLI_ASSOC);



  foreach ($res as $e) {
    $title = $titles[rand(0, 2)];
    $userId = $e['user_id'];
    $sql = "UPDATE users SET title = '$title' WHERE user_id='$userId'";
    $dbConnect->query($sql);
    echo "success";
    echo "<br>";
    echo "<br>";
  }
}
