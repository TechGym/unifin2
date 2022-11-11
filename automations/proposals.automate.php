<?php
include "../includes/functions.php";
include "../functions/notifications.func.php";


// Finalizing proposals
/*
1.  Fetch pending proposals
2.  Check if proposal has reached 48 hours 
3. If so , fetch total number of approvers and rejectors
4. If approved,
    Send a notification to proposal author
    Add to the votes table
    Update status to approved 
5. If rejected ,
    Send a notification to proposal author
    Set status to rejected
*/


function fetchProposals() {
  global $dbConnect;
  $sql = "SELECT proposal_id, author_id , timestamp , approvers , rejectors  FROM proposals WHERE status = 'pending'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function fetchRemainingProposalTime($proposal_time) {
  $currentTime = gmdate("Y-m-d H:i:s");
  $differences =  strtotime($currentTime) - (strtotime($proposal_time) + 3600 * 24 * 2);
  if ($differences >= 0) {
    return false;
  } else {
    return true;
  }
}
function countModerators($moderation_type) {
  if (!$moderation_type) {
    return 0;
  }
  $moderatorsCount = explode(" ", $moderation_type);
  $moderatorsCount = count($moderatorsCount);
  return $moderatorsCount;
}

function determineResults($rejectors, $approvers) {
  if ($approvers >= $rejectors) {
    return true;
  }
  return false;
}

function updateProposalStatus($status, $proposalId) {
  global $dbConnect;
  if ($status) {
    $sql = "UPDATE proposals SET status = 'approved' WHERE proposal_id = '$proposalId' ";
  } else {
    $sql = "UPDATE proposals SET status = 'disapproved' WHERE proposal_id = '$proposalId' ";
  }
  $dbConnect->query($sql);
}

function insertIntoVotes($proposalId) {
  global $dbConnect;
  $timestamp = gmdate("Y-m-d H:i:s");
  $sql = "INSERT INTO votes (proposal_id , timestamp) VALUES ('$proposalId' , '$timestamp' ) ";
  $dbConnect->query($sql);
}


$proposals = fetchProposals();

foreach ($proposals as $proposal) {

  if (!fetchRemainingProposalTime($proposal['timestamp'])) {

    $rejectors = (int) countModerators($proposal["rejectors"]);
    $approvers = (int) countModerators($proposal["approvers"]);
    $proposalResult =  determineResults($rejectors, $approvers);

    updateProposalStatus($proposalResult, $proposal['proposal_id']);
    if ($proposalResult) {
      insertIntoVotes($proposal['proposal_id']);
      insertNewVoteNotification();
    }

    // For proposal author
    insertProposalResultIntoNotifications($proposalResult, $proposal['proposal_id'], $proposal['author_id']);

    // For all council memebers
    insertProposalResultIntoNotifications($proposalResult, $proposal['proposal_id'], "moderators");
  };
}
