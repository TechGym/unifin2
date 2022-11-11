<?php
include "../includes/functions.php";
include "../phpMailer.php";
if (isset($_POST['find'])) {
  $email = htmlspecialchars($_POST['email']);

  $sql = "SELECT email , firstname FROM users WHERE email = '$email'";
  $result = $dbConnect->query($sql);

  if ($result->num_rows == 1) {
    $result = $result->fetch_assoc();
    $firstname = $result['firstname'];
    $path = "../mail/emailEdit.php";

    // Generate code 
    $alp = 'Abcdefghijklmon1234567890';
    $code = "";
    for ($i = 0; $i < 6; $i++) {
      $code .= $alp[rand(0, strlen($alp) - 1)];
    }


    // Send mail
    $mailSend = 'sent';
    // $mailSend = emailEditMail($email, $code, $path, $firstname);
    if ($mailSend == 'sent') {
      // Update email_ref 
      $sql = "UPDATE verifications SET email_ref = '$code' WHERE email ='$email'";
      $dbConnect->query($sql);
      $_SESSION['email'] = $email;
      echo "found";
    }
  } else {
    echo "Email address not found";
  }
}

if (isset($_POST['confirm'])) {
  $code = htmlspecialchars($_POST['code']);
  $email = $_SESSION['email'];

  // Confirm code 
  $sql = "SELECT email_ref FROM verifications WHERE email_ref = '$code' && email = '$email'";
  $result = $dbConnect->query($sql);

  if ($result->num_rows == 1) {
    // Clear email_ref
    $sql = "UPDATE verifications SET email_ref = NULL WHERE email = '$email'";
    $dbConnect->query($sql);
    $_SESSION['confirmed'] = 'true';
    echo "confirmed";
  } else {
    echo "Invalid code";
  }
}


function fetchUserIdWithEmail($email)
{
  global $dbConnect;
  $sql = "SELECT user_id FROM users WHERE email = '$email'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc()['user_id'];
  return $result;
}
if (isset($_POST['reset'])) {
  $email = $_SESSION['email'];
  // Fetch user with email
  $sql = "SELECT  firstname FROM users WHERE email = '$email'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();

  $password = md5(htmlspecialchars($_POST['password']));

  $ip = fetchIp();
  $id = fetchUserIdWithEmail($email);

  $firstname = $result['firstname'];
  $path = "../mail/reset.php";

  $type = 'password reset';
  $timestamp = gmdate("Y-m-d H:i:s");

  // $mailSend = sendPasswordResetMail($email, $firstname, $ip, $timestamp , $path);
  $mailSend = 'sent';
  if ($mailSend == "sent") {
    $sql = "UPDATE users SET password = '$password' WHERE email = '$email'";
    $changes = "INSERT into changes (user_id, type, ip , prev_data , new_data , timestamp) VALUES ('$id' , '$type' , '$ip' , '' , '' ,'$timestamp' )";
    $changes = $dbConnect->query($changes);
    if ($dbConnect->query($sql) && $changes) {
      unset($_SESSION['confirmed']);
      unset($_SESSION['email']);
      echo "reset";
    };
  }
}
