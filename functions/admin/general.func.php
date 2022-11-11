<?php
function memberCount() {
  global $dbConnect;
  $sql = "SELECT current_users FROM sprints ";
  $res = $dbConnect->query($sql);
  $res = $res->fetch_all(MYSQLI_ASSOC);
  return $res;
}

function checkReferralCountOfUserWithIncompleteTeam($user_id) {
  global $dbConnect;
  $sql = "SELECT * FROM membership WHERE referred_by = '$user_id' AND completed = 'false'";
  $result = $dbConnect->query($sql);
  return $result->num_rows;
}


function fetchUsersWithIncompleteTeamStatus() {
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

function checkRemainingDays($membershipDate) {
  $currentTime = gmdate("Y-m-d H:i:s");
  $differences =  strtotime($currentTime) - (strtotime($membershipDate) + 3600 * 24 * 30);

  if ($differences >= 0) {
    return false;
  } else {
    return true;
  }
}

function prepareDueTimeMembers() {
  $members  = fetchUsersWithIncompleteTeamStatus();
  $dueTimeMembers = array_filter($members, function ($arr) {
    return !checkRemainingDays($arr['membership_date']);
  });
  return $dueTimeMembers;
}


// Average time for recruiting 5 members 
// Select all users with completed status
function fetchCompMembers() {
  global $dbConnect;
  $sql = "SELECT u.user_id , u.email, m.membership_date 
    FROM membership m
    JOIN users u
    ON  u.email = m.user_email
    WHERE m.completed = 'true' && m.status = 'member' 
    ORDER BY m.membership_date ASC  ";
  $res = $dbConnect->query($sql);
  $res = $res->fetch_all(MYSQLI_ASSOC);
  return $res;
}

function fetchReferredMembers($userId) {
  global $dbConnect;
  $sql = "SELECT * FROM membership WHERE referred_by = '$userId' && assigned_from is NULL && status = 'member'";
  $res = $dbConnect->query($sql);
  return $res->num_rows;
}

function fetchLastReferral($referrer) {
  global $dbConnect;
  $sql = "SELECT membership_date , id FROM membership WHERE referred_by = '$referrer' && status = 'member' ORDER BY membership_date DESC LIMIT 1 ";
  $res = $dbConnect->query($sql);
  $res = $res->fetch_assoc();
  return $res;
}

function calculateTotalTimeForCompleteTeams() {
  $completedUsers = fetchCompMembers();
  $count = count($completedUsers);
  $sum = 0;

  foreach ($completedUsers as $completedUser) {

    // Check member referrals
    // Check average for only members who don't have pool members as part of their referral team
    $memberReferrals = fetchReferredMembers($completedUser['user_id']);


    if ($memberReferrals === 5) {
      $usersMembershipTime = $completedUser['membership_date'];
      $lastReferralTime = fetchLastReferral($completedUser['user_id'])['membership_date'];
      // echo $lastReferralTime;
      $elapsedTime = strtotime($lastReferralTime) - strtotime($usersMembershipTime);
      $sum += (int) $elapsedTime;
    };
  }
  return $sum;
}

function calculateAverageTime($totalTime, $totalMembers) {
  $avg = (int) $totalTime / $totalMembers;
  return number_format($avg, 2);
}


// Totla people based on gender
function fetchTotalGender($gender) {
  global $dbConnect;
  if ($gender === "male") {
    $sql = "SELECT * 
    FROM users u 
    JOIN membership m
    ON u.email = m.user_email
    WHERE u.title = 'Mr.' && (m.status = 'member' || m.status = 'pool');";
  } else {
    $sql = "SELECT * 
    FROM users u 
    JOIN membership m
    ON u.email = m.user_email
    WHERE (u.title = 'Mrs.' || u.title = 'Miss')&& (m.status = 'member' || m.status = 'pool');";
  }
  $result = $dbConnect->query($sql);
  $result = $result->num_rows;
  return $result;
}

// Fetch age range
// Fetches the number of users in a certain age range
function fetchAgeRange($lower, $upper) {
  global $dbConnect;
  $today = (date("Y-m-d"));
  $year = explode("-", $today);

  $today = strtotime($today);

  $lower = [$year[0] - $lower, $year[1], $year[2] - 1];
  $lower = join("-", $lower);
  $upper = [$year[0] - $upper, $year[1], $year[2]];
  $upper = join("-", $upper);

  $sql = "SELECT u.dob , u.user_id 
  FROM users u
  JOIN membership m
  ON u.email = m.user_email
  WHERE (m.status = 'member' OR m.status = 'pool' ) AND u.dob BETWEEN '$upper . \'23:59:59\' ' AND '$lower' ";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return count($result);
}

function createDistributedTokens() {
  global $dbConnect;
  $sql = "SELECT sum(total) as sum FROM tokens";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  return $result['sum'];
}


function userLogInTimes() {
  global $dbConnect;
  $sql = "SELECT sum(log_count) as sum FROM cookies";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  return $result['sum'];
}

function sendePHI($userId, $amount) {
  global $dbConnect;
  $sql = "UPDATE tokens SET total = total + $amount WHERE user_id = '$userId'";
  $dbConnect->query($sql);

  // Send notification to tell user the received tokens
}
// sendePHI(3, 25);

function fetchAllMembers() {
  global $dbConnect;
  $sql = "SELECT user_id FROM users";
  $dbConnect->query($sql);
}

// For all council of 50 members 
function fetchCouncilOf50() {
  global $dbConnect;
  $sql = "SELECT u.user_id , u.email
    FROM membership m 
    JOIN users u 
    ON u.email = m.user_email
    WHERE m.council = '5' || m.council = '50' ";
  $council = $dbConnect->query(($sql));
  $council = $council->fetch_all(MYSQLI_ASSOC);
  return $council;
}

function fetchCouncilOf5() {
  global $dbConnect;
  $sql = "SELECT u.user_id , u.email
    FROM membership m 
    JOIN users u 
    ON u.email = m.user_email
    WHERE m.council = '5'";

  $council = $dbConnect->query(($sql));
  $council = $council->fetch_all(MYSQLI_ASSOC);

  return $council;
}


function fetchSprintMembers($sprint) {
  global $dbConnect;
  $endPoints = [0, 50, 250, 1250, 6250, 31250, 156250, 781250, 3906250, 19531250];
  $upper = $endPoints[$sprint];
  $lower = $endPoints[(int) $sprint - 1];
  $limit = $upper - $lower;
  $skip = $lower;
  $sql = "SELECT u.user_id 
    FROM membership m
    JOIN users u
    ON  u.email = m.user_email
    WHERE m.status = 'pool' || m.status = 'member'
    ORDER BY m.membership_date ASC
    LIMIT $skip , $limit";
  $res = $dbConnect->query($sql);
  return $res->fetch_all(MYSQLI_ASSOC);
}

$sprintMembers  = fetchSprintMembers(2);

function fetchAllReferrals($id) {
  global $dbConnect;
  $sql = "SELECT *  FROM membership WHERE assigned_from = '$id' || referred_by = '$id'";
  $result = $dbConnect->query(($sql));
  return $result->num_rows;
}


function fetchMembersWithPlus5Referrals() {
  // All users who have their team completed are potential +5 referrers
  global $dbConnect;
  $sql = "SELECT user_email , id FROM membership WHERE completed = 'true'";
  $users = $dbConnect->query(($sql));
  $users = $users->fetch_all(MYSQLI_ASSOC);

  // loop through each and check the number of referrals or assigned to
  $plus5referrers = [];
  foreach ($users as $user) {
    $totalReferrals = fetchAllReferrals($user['id']);

    if ($totalReferrals > 5) {
      array_push($plus5referrers, $user);
    }
  }
  var_dump($plus5referrers);
}

// fetchMembersWithPlus5Referrals();

function updateTokens($email, $amount) {
  global $dbConnect;
  $sql = "UPDATE tokens SET total = total + $amount , awards = awards + $amount WHERE user_email ='$email'";
  return $dbConnect->query($sql);
}
