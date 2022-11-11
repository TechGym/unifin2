<?php
include "../includes/functions.php";
/*
1. Select voters of the proposal
2. If approvers is NULL update without concatination else concat voters, " " and current userId 

*/
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

/*
1. Select voters of the proposal
2. If voters is NULL update without concatination else concat voters, " " and current userId 
*/
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
