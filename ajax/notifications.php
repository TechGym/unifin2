<?php
include_once "../includes/functions.php";
if (isset($_POST['markRead'])) {
  $id = htmlspecialchars($_POST['id']);
  $type = htmlspecialchars($_POST['type']);
  $userId = $loggedInfo['user_id'];
  $timestamp = gmdate("Y-m-d H:i:s");

  if ($type == 'personal') {
    $sql = "UPDATE notifications SET status = 'read' , read_time = '$timestamp' WHERE notification_id = '$id'";
  } else {
    $sql = "UPDATE notifications SET status = trim(CONCAT(status , ' ' , '$userId' )) WHERE notification_id = '$id'";
  }
  $result = $dbConnect->query($sql);
  if ($result) {
    echo "success";
  }
}
