<?php
/*
 * Insert Proposal result Notification
 * 
 * Add a notification on the notifications table to tell council members the result of a proposal after moderation 
 * 
 * @param String
 * @param Number 
 * @para, Number
 * 
 * @return void
*/
function insertProposalResultIntoNotifications($proposalResult, $proposalId, $authorId) {
  global $dbConnect;
  if ($proposalResult) {
    $category = "approved";
  } else {
    $category = "rejected";
  }
  $timestamp = gmdate("Y-m-d H:i:s");
  $type = "proposal";
  $link = './proposals?id=' . $proposalId;
  $sql = "INSERT INTO notifications(user_id , type , category , timestamp , link )
    VALUES('$authorId' , '$type' , '$category' , '$timestamp' , '$link' )";

  if ($authorId == 'moderators') {
    $sql = "INSERT INTO notifications(user_id , type , category , timestamp , link , status)
    VALUES('$authorId' , '$type' , '$category' , '$timestamp' , '$link' , ' ')";
  }
  $dbConnect->query($sql);
}

/*
 * Insert Vote result Notification
 * 
 * Add a notification on the notifications table to tell  members the result of a vote after voting ends 
 * 
 * @param String
 * @param Number 
 * @param Number
 * 
 * @return void
*/
function insertVoteResultIntoNotifications($result, $voteId, $authorId) {
  global $dbConnect;
  if ($result) {
    $category = "approved";
  } else {
    $category = "rejected";
  }
  $timestamp = gmdate("Y-m-d H:i:s");
  $type = "votes";
  $link = './votes?id=' . $voteId;
  $sql = "INSERT INTO notifications(user_id , type , category , timestamp , link , status)
    VALUES('$authorId' , '$type' , '$category' , '$timestamp' , '$link' , 'unread')";

  if ($authorId == 'voters') {
    $sql = "INSERT INTO notifications(user_id , type , category , timestamp , link , status)
    VALUES('$authorId' , '$type' , '$category' , '$timestamp' , '$link' , ' ')";
  }

  $dbConnect->query($sql);
}

/*
 * Insert member notification
 * 
 * Add a notification on the notifications table to inform a referrer that his/her referral is now a member 
 * @param String
 * @param String 
 * @param Number
 * @param String 
 * 
 * @return void
*/
function insertMemberNotification($referred_by, $category, $amount, $type) {
  global $dbConnect;
  $timestamp = gmdate("Y-m-d H:i:s");
  $link = "./referral";
  if ($type == 'pool') {
    $link = "./";
  }
  $sql = "INSERT into notifications(user_id, type ,amount, category , timestamp , link , sender_email) VALUES ('$referred_by' , '$type' ,'$amount', '$category' , '$timestamp' , '$link' , 'system');";
  $dbConnect->query($sql);
}

/*
 * Insert milestone notification
 * 
 * Add a notification on the notifications table to inform a referrer that he/she has reached the milestone of 5 referrals

 * @param Number
 * 
 * @return void
*/
function sendCompletedMilestoneNotification($userId) {
  global $dbConnect;

  $number = count(fetchMemberReferrals($userId));

  if ($number == 5) {
    $type = "milestone";
    $timestamp = gmdate("Y-m-d H:i:s");
    $link = "./referral";
    $sql = "INSERT into notifications(user_id, type , category , timestamp , link) VALUES ('$userId' , '$type' , 'completed' , '$timestamp' , '$link');";
    $dbConnect->query($sql);
  }
}

/*
 * Insert received tokens notification
 * 
 * Add a notification on the notifications table to inform a user that he/she received some amount of tokens

 * @param Number
 * @param String
 * @param Number
 * 
 * @return void
*/
function insertReceivedTokens($amount, $category, $userId) {
  global $dbConnect;
  if ($userId != '0') {
    $type = "tokens";
    $timestamp = gmdate("Y-m-d H:i:s");
    $link = "./profile";
    if ($category == 'pool') {
      $link = "./campaign";
    }
    $sql = "INSERT into notifications(user_id, type , amount, category , timestamp , link) VALUES ('$userId' , '$type', '$amount' , '$category' , '$timestamp' , '$link');";
    $dbConnect->query($sql);
  }
}

/*
 * Insert a new proposal notification
 * 
 * Add a notification on the notifications table to inform council members of a new proposal

 * @param Number
 * 
 * @return void
*/
function insertNewProposal($proposalId) {
  global $dbConnect;
  $type = "proposal";
  $category = "new";
  $timestamp = gmdate("Y-m-d H:i:s");
  $link = "./proposals?id=" . $proposalId;
  $sql = "INSERT into notifications(user_id, type , category , timestamp , link , status) VALUES ('moderators' , '$type' , '$category' , '$timestamp' , '$link' , '');";
  $dbConnect->query($sql);
}

/*
 * Insert a new sprint notification
 * 
 * Add a notification on the notifications table to inform  members of a new sprint

 * @param Number
 * 
 * @return void
*/
function insertNewSprintNotification($sprint) {
  global $dbConnect;
  $type = "sprint";
  $amount = fetchCurrentSprint();
  $category = "new";
  $timestamp = gmdate("Y-m-d H:i:s");
  $link = "./campaign";
  $sql = "INSERT into notifications(user_id, type ,amount, category , timestamp , link , status) VALUES ('voters' , '$type' ,'$amount', '$category' , '$timestamp' , '$link' , '');";
  $dbConnect->query($sql);
}

/*
 * Insert a new vote notification
 * 
 * Add a notification on the notifications table to inform  members that a new proposal is ready to be voted on
 * 
*/
function insertNewVoteNotification() {

  global $dbConnect, $loggedInfo;
  // Fetch current vote 
  $sql = " SELECT * FROM votes ORDER BY timestamp DESC LIMIT 1 ";
  $result = $dbConnect->query($sql);
  $voteId =  $result->fetch_assoc();
  $voteId = $voteId['vote_id'];

  $type = "votes";
  $category = 'new';
  $userId = 'voters';
  $link = './votes?id=' . $voteId;
  $timestamp = gmdate("Y-m-d H:i:s");

  $sql = "INSERT into notifications(user_id, type ,amount, category , timestamp , link , status) VALUES ('$userId' , '$type' , NULL , '$category' , '$timestamp' , '$link' , ' ');";
  $dbConnect->query($sql);
}

/*
 * Insert a new tip notification
 * 
 * Add a notification on the notifications table to inform  members that a new proposal is ready to be voted on
 * 
*/
function insertTip($group, $amount) {

  global $dbConnect, $loggedInfo;

  $type = "tip";
  // Category here means the user who sent the tips 
  $category = $loggedInfo['email'];
  $userId = $group;
  $link = './profile';
  $timestamp = gmdate("Y-m-d H:i:s");

  $sql = "INSERT into notifications(user_id, type ,amount, category , timestamp , link , status) VALUES ('$userId' , '$type' , '$amount' , '$category' , '$timestamp' , '$link' , ' ');";
  return $dbConnect->query($sql);
}

/*
 * fetch all notifications of a loggedIn user(current user)
 * 
 * Fetch all the notiifcations of a logged in user 
 * 
 * @return String[]
*/
function fetchAllNotifications() {
  global $dbConnect, $loggedInfo;
  $userId = $loggedInfo['user_id'];
  $status = fetchStatus($loggedInfo['email']);
  $membershipDate = fetchMembershipDate($loggedInfo['email']);

  if ($status === "pending") {
    // User is a pending user hence should see only notifications directed to him / her 
    $sql = "SELECT * FROM notifications WHERE user_id = '$userId'";
  } else {
    // var_dump($membershipDate);
    $sql = "(
  SELECT * FROM notifications WHERE user_id = '$userId' AND timestamp >= '$membershipDate' 
  UNION SELECT * FROM notifications WHERE user_id = 'moderators'  AND timestamp > '$membershipDate' 
  UNION SELECT * FROM notifications WHERE user_id = 'voters' AND timestamp > '$membershipDate' 
  ) 
  ORDER BY timestamp DESC";
  }
  // SELECT MEMBERSHIP DATE

  $result = $dbConnect->query($sql);
  return $result->fetch_all(MYSQLI_ASSOC);
}
/**
 * Check the read status of a notification
 */
function checkReadStatus($users) {
  global $loggedInfo;
  $userId = $loggedInfo['user_id'];
  $users = explode(" ", $users);
  return in_array($userId, $users);
}

/**
 * Filter notiifcations and set unreadNotifications count
 * 
 * Filter the notifications into read and unread based on the status of the notification
 * 
 *  Set unread notifications count
 * @param Array
 * @return Array
 */
$unreadNotificationsCount = 0;
function filterNotifications($notifications) {
  global $unreadNotificationsCount;
  global $loggedInfo;
  $council = checkUsersCouncil($loggedInfo['email']);
  $readNotifications = [];
  $unreadNotifications = [];

  // Checking read and unread status
  foreach ($notifications as $notification) {
    $status = $notification['status'];
    $type = $notification['user_id'];

    // Setting personal and general
    $general = false;
    if ($type == 'moderators' || $type == 'voters') {
      $general = true;
    }

    // Checking read and unread status for personal notifications
    if (!$general) {
      if ($status == "unread") {
        $notification['status'] = 'unread';
        array_push($unreadNotifications, $notification);
      } elseif ($notification['status'] == 'read') {
        $notification['status'] = 'read';
        array_push($readNotifications, $notification);
      }
    } else {
      // Check if loggedUser is part of council in order to receive notifications about proposals
      if ($type == 'moderators' && $council != '5' && $council != "50") {
      } else {
        // This part of the code is for general notifications
        $readStatus = checkReadStatus($notification['status']);
        if ($readStatus) {

          $notification['status'] = 'read';
          array_push($readNotifications, $notification);
        } else {
          $notification['status'] = 'unread';
          array_push($unreadNotifications, $notification);
        }
      }
    }
  }
  $unreadNotificationsCount = count($unreadNotifications);
  return [...$unreadNotifications, ...$readNotifications];
}

/**
 * Extract the id of a proposal, vote from a notifcation's link. 
 * @param String 
 * @return String
 */
function extractId($link) {
  $arr = explode("id=", $link);
  return padId($arr[1]);
}

/**
 * Extract the heading of a proposal, vote usinf the id of it. 
 * @param Number 
 * @return String
 */
function fetchVoteHeading($voteId) {
  global $dbConnect;
  $sql = "SELECT p.heading 
  FROM proposals p
  JOIN votes v 
  ON p.proposal_id = v.proposal_id
  WHERE v.vote_id = '$voteId'
  ";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  return $result["heading"];
}

/**
 * Create a Notification preview message 
 * 
 * Create a message to be displayed in the notifications tab of  a logged in user
 * @param String[]
 * @param Number
 * 
 * @return String[]
 */
function createNotificationsMessage($notification, $id = 0) {
  global $loggedInfo;
  $type = $notification['type'];
  $category = $notification['category'];
  $amount = $notification['amount'];
  $users = 'personal';
  $userId = $notification['user_id'];
  $notification_elem_id = "";
  $voteHeading = "";

  $loggedUserId = $loggedInfo['user_id'];


  if ($type == 'proposal') {
    $notification_elem_id = "#" .  extractId($notification['link']);
  }


  if ($type == 'votes') {
    $voteId = extractId($notification['link']);

    $voteHeading = strtolower(fetchVoteHeading($voteId));
  }

  if ($userId == 'moderators' || $userId == 'voters') {
    $users = 'general';
  }

  if ($type == 'tokens' && $category == 'membership') {
    return [
      "message" => "You have received $amount ePHI for registering on unifin.cc ",
      "heading" => "You received tokens",
      "src" => "reward.svg"
    ];
  }
  if ($type == 'milestone') {
    return [
      "message" => "You have  $amount member referrals on unifin.cc ",
      "heading" => "Milestone reached",
      "src" => "milestone.svg"
    ];
  }
  if ($type == 'tip') {
    $sender_email = $notification['sender_email'];
    return [
      "message" => "You have  recived $amount ePHI from $sender_email ",
      "heading" => "Tokens received ",
      "src" => "reward.svg"
    ];
  }
  if ($type == 'tokens' && $category == 'referral') {
    return [
      "message" => "You have received $amount ePHI for referring a member ",
      "heading" => "You received tokens",
      "src" => "reward.svg"
    ];
  }
  if ($type == 'referral' && $category == 'new') {
    return [
      "message" => "You have received $amount new referral",
      "heading" => "New referral",
      "src" => "referral.svg"
    ];
  }
  if ($type == 'proposal' && $category == 'new') {
    return [
      "message" => "A new proposal of id $notification_elem_id has been created and is waiting for moderation",
      "heading" => "New proposal",
      "src" => "newproposal.svg"
    ];
  }
  if ($type == 'proposal' && $category == 'approved' && $users == 'personal') {
    return [
      "message" => "Your proposal of id $notification_elem_id has been approved by the council and is being voted on",
      "heading" => "Approved proposal",
      "src" => "approvedproposal.svg"
    ];
  }
  if ($type == 'proposal' && $category == 'rejected' && $users == 'personal') {
    return [
      "message" => "Your proposal of id $notification_elem_id has been disapproved by the council ",
      "heading" => "Disapproved proposal",
      "src" => "rejected.svg"
    ];
  }
  if ($type == 'proposal' && $category == 'approved' && $users == 'general') {
    return [
      "message" => "A proposal of id $notification_elem_id has been approved by the council and is being voted on",
      "heading" => "Approved proposal",
      "src" => "approvedproposal.svg"
    ];
  }
  if ($type == 'proposal' && $category == 'rejected' && $users == 'general') {
    return [
      "message" => "A proposal of id $notification_elem_id has been disapproved by the council ",
      "heading" => "Disapproved proposal",
      "src" => "rejected.svg"
    ];
  }
  if ($type == 'pool' && $category == 'new' && $users == 'personal') {
    return [
      "message" => "One of your referrals is in the pool, you will receive tokens when they are distributed",
      "heading" => "New pool member proposal",
      "src" => "reward.svg"
    ];
  }
  if ($type == 'votes' && $category == 'rejected' && $users == 'general') {
    return [
      "message" => "A vote of $voteHeading has been rejected by voters",
      "heading" => "Vote results",
      "src" => "rejected.svg"
    ];
  }
  if ($type == 'votes' && $category == 'approved' && $users == 'general') {
    return [
      "message" => "A vote of $voteHeading has been accepted by voters",
      "heading" => "Vote results",
      "src" => "approved.svg"
    ];
  }
  if ($type == 'votes' && $category == 'rejected' && $users == 'personal') {
    return [
      "message" => "Your vote of $voteHeading has been rejected by voters",
      "heading" => "Rejected proposal",
      "src" => "rejected.svg"
    ];
  }
  if ($type == 'votes' && $category == 'approved' && $users == 'personal') {
    return [
      "message" => "Your vote of $voteHeading has been approved by voters and is waiting completion",
      "heading" => "Approved proposal",
      "src" => "approved.svg"
    ];
  }
  if ($type == 'votes' && $category == 'new') {
    return [
      "message" => "A new proposal of $voteHeading is up for voting",
      "heading" => "Time to vote",
      "src" => "newvote.svg"
    ];
  }
  if ($type == 'referral' && $category == 'distribution') {
    return [
      "message" => "You have received $amount members from the pool",
      "heading" => "Members received ",
      "src" => "team.svg"
    ];
  }
  if ($type == 'tokens' && $category == 'pool') {
    return [
      "message" => "You have received $amount tokens from a pool member's distribution",
      "heading" => "Tokens received ",
      "src" => "reward.svg"
    ];
  }
  if ($type == 'sprint' && $category == 'new') {
    return [
      "message" => "A new sprint of id #00$amount has been started",
      "heading" => "New Sprint started",
      "src" => "sprint.svg"
    ];
  }
}

/**
 * Create a time elapsed message (Eg: 2 weeks ago )
 * 
 * Create a message to tell the time elapsed based o a particular event 
 * @param Number
 * 
 * @return String
 */
function time_elapsed_string($ptime) {
  $etime = strtotime(gmdate("Y-m-d H:i:s")) - $ptime;

  if ($etime < 1) {
    return '0 seconds';
  }

  $a = array(
    365 * 24 * 60 * 60  =>  'year',
    30 * 24 * 60 * 60  =>  'month',
    7 * 24 * 60 * 60  =>  'week',
    24 * 60 * 60  =>  'day',
    60 * 60  =>  'hour',
    60  =>  'minute',
    1  =>  'second'
  );
  $a_plural = array(
    'year'   => 'years',
    'month'  => 'months',
    'week'    => 'weeks',
    'day'    => 'days',
    'hour'   => 'hours',
    'minute' => 'minutes',
    'second' => 'seconds'
  );

  foreach ($a as $secs => $str) {
    $d = $etime / $secs;
    if ($d >= 1) {
      $r = floor($d);
      return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
    }
  }
}


/**
 * Create all notifications
 * 
 * Creates an array that holds all the information about a notification
 * 
 * 1. Fetch all notifications of loggedIn user both read and unread
 * 2. Filter notifications 
 *    Check read and unread
 *    Check personal and general notifications
 * 3. Create notification heading
 * 4. Create notification content
 *
 * 
 * @return String[]
 */

function createNotifications() {
  $notifications = [];
  $notices = fetchAllNotifications();

  $filteredNotifications =  filterNotifications($notices);

  foreach ($filteredNotifications  as $notification) {

    ["message" => $message, "heading" => $heading, "src" => $src]  = createNotificationsMessage($notification);
    $status = $notification['status'];
    $timestamp = time_elapsed_string((int) strtotime($notification['timestamp']));
    $userId = $notification['user_id'];

    if ($userId == 'moderators' || $userId == 'voters') {
      $type = 'general';
    } else {
      $type = 'personal';
    }

    $arr = [
      "status" => $status,
      "message" => $message,
      "src" => $src,
      "heading" => $heading,
      "timestamp" => $timestamp,
      "link" => $notification['link'],
      "id" => $notification['notification_id'],
      "type" => $type,
    ];
    array_push($notifications, $arr);
  }
  return $notifications;
}
