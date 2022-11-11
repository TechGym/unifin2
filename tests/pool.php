<?php
include_once "../includes/functions.php";
include_once "../functions/index.func.php";
include_once "../functions/notifications.func.php";

$dead = 2;
if (isset($dead)) {
  for ($i = 0; $i < 1; $i++) {
    $referrers = [2, 5, 4, 6];

    $email = md5(uniqid());
    $password = md5($email);
    $code = md5(uniqid());

    $date = gmdate("Y-m-d H:i:s");

    $council = checkCouncilStatus();

    // $referred_by = $referrers[rand(0, count($referrers) - 1)];
    $referred_by = "5";

    // fetching referrer email
    $sql = "SELECT * FROM users WHERE user_id = '$referred_by'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_assoc();
    $referrerEmail = $result['email'];

    // Insert into membership
    $memberReferrals = count(fetchMemberReferrals($referred_by));


    if ($memberReferrals == 5) {
      $status = 'pool';
    } else {
      $status = 'member';
    }

    if ($council != "counciMembersComplete") {
      $sql = "INSERT INTO membership (user_email , referred_by , status , membership_date , referral_code , council)
      VALUES ('$email' , '$referred_by' , '$status' , '$date' , '$code' , '$council') ";
    } else {
      $sql = "INSERT INTO membership (user_email , referred_by , status , membership_date , referral_code , council)
      VALUES ('$email' , '$referred_by' , '$status' , '$date' , '$code' , NULL) ";
    }

    $membership = $dbConnect->query($sql);

    $sql = "INSERT INTO users (email , firstname , lastname , password,  joined_date  , country)
      VALUES ('$email' , 'Asare' , 'Foster' , '$password' , '$date' , 'Ghana') ";

    $users = $dbConnect->query($sql);

    if ($membership && $users) {
      // Fetch User Id 
      $sql = "SELECT * FROM users WHERE email = '$email'";

      $result = $dbConnect->query($sql);

      $user = $result->fetch_assoc();
      $userId = $user['user_id'];

      // Insert notifications 
      insertReceivedTokens(25, "membership", $userId);
    }

    if ($memberReferrals == 4) {
      // Insert milestone notification
      insertMilestoneNotification($referred_by, $date);

      // Update completed Status
      $completed = "UPDATE membership SET completed = 'true' WHERE user_email = '$referrerEmail';";
      $dbConnect->query($completed);
    }
    if ($memberReferrals < 4) {
      insertReceivedTokens(75, "referral", $referred_by);
    }
  }
}
