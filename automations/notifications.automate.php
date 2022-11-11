<?php
include "../includes/functions.php";
include "../functions/notifications.func.php";


// Delete read notifications after 15 minutes
function findPersonalReadNotifications() {
  global $dbConnect, $loggedInfo;
  $sql = "SELECT * FROM notifications WHERE user_id NOT IN ('moderators' , 'voters') && status = 'read'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function findGeneralReadNotifications() {
  global $dbConnect, $loggedInfo;
  $sql = "SELECT * FROM notifications WHERE user_id IN ('moderators' , 'voters')";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

// Find timestamp
function checkElapsedTime($timestamp) {
  $elapsed = false;
  $current = strtotime(gmdate("Y-m-d H:i:s"));
  $start = $timestamp;
  $diff = $start - $current;
  if ($diff < 0) {
    $elapsed = true;
  }
  return $elapsed;
}

$readNotifications  = findPersonalReadNotifications();

function deleteNotification($notificationId) {
  global $dbConnect;
  $sql = "DELETE FROM notifications WHERE notification_id = '$notificationId';";
  return $dbConnect->query($sql);
}
foreach ($readNotifications as $readNotification) {
  $timestamp = strtotime($readNotification['read_time']) + (60 * 15);
  $elapsed = checkElapsedTime($timestamp);
  if ($elapsed) {
    // Delete notification
    if (deleteNotification($readNotification['notification_id'])) {
      echo "deleted";
    };
  } else {
    var_dump($readNotification);
  }
  echo "<br>";
  echo "<br>";
}

$readGeneralNotifications = findGeneralReadNotifications();

foreach ($readGeneralNotifications as $readGeneralNotification) {
  $timestamp = strtotime($readGeneralNotification['timestamp']) + (3600 * 24 * 3);
  $elapsed = checkElapsedTime($timestamp);
  if ($elapsed) {
    // Delete notification
    if (deleteNotification($readGeneralNotification['notification_id'])) {
      echo "deleted";
    };
  } else {
    // var_dump($readNotification);
  }
  echo "<br>";
  echo "<br>";
}



// Delete personal notifications after 2 days when not read 
function findAllPersonalNotifications() {
  global $dbConnect, $loggedInfo;
  $sql = "SELECT * FROM notifications WHERE user_id NOT IN ('moderators' , 'voters')";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function checkCredibility($timestamp, $days) {
  $time = strtotime($timestamp);
  $currentTime = strtotime(gmdate("Y-m-d H:i:s")) + (3600 * 24 * (int) $days);
  $diff = $time - $currentTime;
  if ($diff > 0) {
    return 'elapsed';
  }
  return 'active';
}
$findAllPersonalNotifications = findAllPersonalNotifications();
foreach ($findAllPersonalNotifications as $personalNotification) {
  $credibiility = checkCredibility($personalNotification['timestamp'], 2);
  if ($credibiility === "elapsed") {
    deleteNotification($personalNotification['notification_id']);
  }
}

// Delete moderation and voting notifications after 3 days
function findAllGeneralNotifications() {
  global $dbConnect, $loggedInfo;
  $sql = "SELECT * FROM notifications WHERE user_id IN ('moderators' , 'voters')";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

$findAllGeneralNotifications = findAllGeneralNotifications();
foreach ($findAllGeneralNotifications as $generalNotification) {
  $credibiility = checkCredibility($generalNotification['timestamp'], 3);
  if ($credibiility === "elapsed") {
    deleteNotification($generalNotification['notification_id']);
  } else {
  }
}
