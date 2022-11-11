<?php
include "../includes/functions.php";
include "../functions/notifications.func.php";

// Distributing pool members
/*
1. Fetch all users who are in the pool and also fetch all users who have not a complete referral team 
2. Use membership date to verify if user has elapsed their time of 30 days
3. Fetch memberReferrals of user if time has been elapsed
4. Fetch members from the pool based on the number of referrals left to reach 5
5. Iterating over $poolMembers to grab their referrer
6. Assign pool member to current user(Set referred_by to current user's id and set assigned_from to referrer's id)
7. Update referrer's tokens and total tokens in current sprint
8 . Fetch the memberReferrals of user to see if pool members were enough to fill user's referral team.
if so change completed to true
*/
// Fetching the id of members who do not have a complete team 
function fetchMembers() {
  global $dbConnect;
  $sql = "SELECT u.user_id , u.email, m.membership_date 
    FROM membership m
    JOIN users u
    ON  u.email = m.user_email
    WHERE m.completed = 'false' && m.status = 'member'
    ORDER BY m.membership_date ASC  ";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function fetchPoolMembers($limit) {
  global $dbConnect;
  $sql = "SELECT user_email  FROM membership WHERE status = 'pool' ORDER BY membership_date ASC LIMIT $limit";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function checkRemainingDays($membershipDate) {
  $currentTime = gmdate("Y-m-d H:i:s");
  $differences =  strtotime($currentTime) - (strtotime($membershipDate) + 3600 * 24 * 30);

  if ($differences >= 0) {
    return false;
  } else {
    return true;
  }
}

function assignPoolMember($poolMemberEmail, $userId, $assignedFrom) {
  global $dbConnect;
  $sql = "UPDATE membership SET referred_by = '$userId' , assigned_from = '$assignedFrom',  status = 'member' WHERE user_email = '$poolMemberEmail'  ";
  $dbConnect->query($sql);
}

function updateReferrerTokens($referred_by) {
  global $dbConnect;
  if ($referred_by != '0') {
    $sql = "UPDATE tokens SET awards =awards + 75 , total = total + 75  WHERE user_id = '$referred_by'  ";
    $dbConnect->query($sql);
  }
}
function updateReceivedTokens($currentSprint) {
  global $dbConnect;
  $sql = "UPDATE sprints SET tokens_received = tokens_received + 75 WHERE sprint_id = '$currentSprint'  ";
  $dbConnect->query($sql);
}

function changeCompletedStatus($id, $email) {
  $memberReferrals = count(fetchMemberReferrals($id));
  if ($memberReferrals == 5) {
    global $dbConnect;
    $email = $email;
    $sql = "UPDATE membership SET completed = 'true'   WHERE user_email = '$email'  ";
    $dbConnect->query($sql);
  }
}



$users = fetchMembers();


foreach ($users as $user) {


  // Check if user has elapsed their time of 30 days 
  if (!checkRemainingDays($user['membership_date'])) {

    $memberReferrals = count(fetchMemberReferrals($user['user_id']));

    $poolMembers = fetchPoolMembers(5 - $memberReferrals);

    if (count($poolMembers) > 0) {
      foreach ($poolMembers as $poolMember) {
        $referred_by = fetchReferrer($poolMember["user_email"]);

        assignPoolMember($poolMember['user_email'], $user["user_id"], $referred_by);

        updateReferrerTokens($referred_by);

        // Notify referrer of receive tokens after pool distribution
        insertReceivedTokens(75, "pool", $referred_by);

        // Insert a transaction for referrer whose referral has been assigned 
        insertTransaction($referred_by, "75", "system", "distribution");

        $currentSprint = fetchCurrentSprint();
        updateReceivedTokens($currentSprint);
      }
      // Change status of user who received referrals to completed if they had  a complete set of referrals
      changeCompletedStatus($user['user_id'], $user['email']);

      // Insert into notifications to notify user of how many referrals they received from the pool
      insertMemberNotification($user['user_id'], "distribution",  count($poolMembers), "referral");

      // Sending notification if the user has reached the milestone
      sendCompletedMilestoneNotification($user["user_id"]);
    }
  }
}
