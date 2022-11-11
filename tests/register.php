<?php
include "../includes/functions.php";
include "../functions/register.func.php";

for ($i = 0; $i < 5; $i++) {
  $unique = uniqid();
  $_POST['firstname'] = "Foster" . $unique;
  $_POST['lastname'] = "Asare" . $unique;
  $_POST['email']  = md5($_POST['firstname']) . "@gmail.com";
  $_POST['password'] = $_POST['email'];
  $_POST['country'] = "Ghana";
  $_POST['state'] = "";
  $_POST['city'] = "";
  $_POST['address'] = "";

  if (isset($_POST['firstname'])) {
    $firstName = ucwords(htmlspecialchars($_POST['firstname']));
    $lastName = ucwords(htmlspecialchars($_POST['lastname']));
    $password = htmlspecialchars($_POST['password']);
    $email = strtolower(htmlspecialchars($_POST['email']));
    $country = ucwords(htmlspecialchars($_POST['country']));
    $state = ucwords(htmlspecialchars($_POST['state']));
    $city = ucwords(htmlspecialchars($_POST['city']));
    $address = ucwords(htmlspecialchars($_POST['address']));
    $date = gmdate("Y-m-d H:i:s");

    // Error Handling
    $error = checkEmptyFields($firstName, $lastName, $email, $password, $country, $date);
    if ($error == false) {

      // Hash password
      $password = md5($password);

      $email_ref = generateKey();

      // $referral_by =  findReferral();
      $referral_by = '1';

      // $referral_by = rand(1, 25);

      $emailStatus = checkEmail($email);

      if ($emailStatus !== true) {
        $err = $emailStatus;
      } else {
        // $mailSend = sendVerificationEmail($email, $email_ref);
        $mailSend = "sent";
        if ($mailSend == "sent") {
          $verifications = insertIntoVerifications($email, $email_ref);
          $referral = insertReferral($email, $referral_by);
          $user = insertUser($firstName, $lastName, $email, $password, $country, $city, $state, $address, $date, "", "");
          if ($user && $referral && $verifications) {
            // Clear ref cookie and set an email session  to be used for verifications
            setcookie("ref", "", time() - 3600 * 24 * 30, "/");
            // Setting sessions
            $_SESSION["email"] = $_POST['email'];
            sleep(2);
            // header("Location: ./verifications.php?type=verifyemail");
          } else {
            echo "error";
          }
        }
      }
    } else {
      $err = $error;
    }
  }
}
