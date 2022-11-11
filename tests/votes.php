<?php
include_once "../includes/functions.php";
include "../functions/votes.func.php";
include "../functions/notifications.func.php";

function fetchAllMembers()
{
  global $dbConnect;
  $sql = "SELECT u.user_id
  FROM users u
  JOIN membership m 
  ON u.email = m.user_email
  WHERE m.status = 'pool' || m.status = 'member';";
  $result = $dbConnect->query($sql);

  $voters = [];
  while ($row = $result->fetch_assoc()) {

    array_push($voters, $row);
  }

  return $voters;
}
$vote = true;
$choices = ['accept', 'reject'];
if (isset($vote)) {
  // Create random proposal moderations
  $voters = fetchAllMembers();

  $votes = fetchActiveVotes();

  foreach ($votes as $vote) {
    foreach ($voters as $voter) {
      echo $vote['vote_id'];
      $_POST["voteId"] = $vote['vote_id'];
      $loggedInfo["user_id"] = $voter["user_id"];


      $choice = $choices[rand(0, count($choices) - 1)];

      if ($choice == 'accept') {
        $_POST["approve"] = true;
        if (isset($_POST["approve"])) {
          $voteId = +$_POST["voteId"];
          $userId = $loggedInfo["user_id"];

          // 1
          $sql = "SELECT voters FROM votes  WHERE vote_id = '$voteId' ";
          $result = $dbConnect->query($sql);
          $result = $result->fetch_assoc()['voters'];

          // 2. 

          $sql = "UPDATE votes SET  voters = CONCAT(voters ,' ' , $userId) , yes_count = yes_count + 1  WHERE vote_id = '$voteId' ";
          if (!$result) {
            $sql = "UPDATE votes SET  voters =$userId,  yes_count = yes_count + 1  WHERE vote_id = '$voteId' ";
          }

          $result = $dbConnect->query($sql);
          if ($result) {
            echo "success";
          }
        }
      } else {
        $_POST["reject"] = true;
        if (isset($_POST["reject"])) {
          $voteId = +$_POST["voteId"];
          $userId = $loggedInfo["user_id"];

          // 1
          $sql = "SELECT voters FROM votes  WHERE vote_id = '$voteId' ";
          $result = $dbConnect->query($sql);
          $result = $result->fetch_assoc()['voters'];

          // 2. 

          $sql = "UPDATE votes SET  voters = CONCAT(voters ,' ' , $userId),  no_count= no_count + 1  WHERE vote_id = '$voteId' ";
          if (!$result) {
            $sql = "UPDATE votes SET  voters = $userId , no_count= no_count + 1  WHERE vote_id = '$voteId' ";
          }
          $result = $dbConnect->query($sql);
          if ($result) {
            echo "success";
          }
        }
      }

      echo "<br>";
      // echo "<br>";
    }
  }
}
