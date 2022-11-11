<?php
include "../includes/functions.php";
include "../functions/notifications.func.php";


// Finalizing votes
/*
1.  Fetch pending votes
2.  Check if vote has reached 72 hours 
3. If so , assign total number of yes_count and no_count
4. If yes wins,
    Send a notification to proposal author and all other member that the vote was approved 
    Update status to approved 
5. If rejected ,
    Send a notification to proposal author
    Set status to rejected
*/

function fetchVotes() {
  global $dbConnect;
  $sql = "SELECT vote_id, yes_count , no_count , timestamp , proposal_id  FROM votes WHERE status = 'active'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function fetchRemainingVoteTime($vote_time) {
  $currentTime = gmdate("Y-m-d H:i:s");
  $differences =  strtotime($currentTime) - (strtotime($vote_time) + 3600 * 24 * 3);
  if ($differences >= 0) {
    return false;
  } else {
    return true;
  }
}

function determineVoteResults($yes_count, $no_count) {
  if ($yes_count >= $no_count) {
    return true;
  }
  return false;
}

function updateVoteStatus($result, $voteId, $proposalId) {
  global $dbConnect;
  if ($result) {
    $sql = "UPDATE votes SET status = 'approved' WHERE vote_id = '$voteId' ";
  } else {
    $sql = "UPDATE votes SET status = 'rejected' WHERE vote_id = '$voteId' ";
    $proposal = "UPDATE proposals SET status = 'rejected' WHERE proposal_id = '$proposalId' ";
    $dbConnect->query($proposal);
    // Update proposal to have a status of rejected 
  }
  $dbConnect->query($sql);
}

function fetchAuthorId($voteId) {
  global $dbConnect;
  $sql = "SELECT p.author_id 
  FROM votes v 
  JOIN proposals p 
  ON v.proposal_id = p.proposal_id 
  WHERE v.vote_id = '$voteId'";

  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  return $result['author_id'];
}
$votes = fetchVotes();

foreach ($votes as $vote) {

  if (!fetchRemainingVoteTime($vote['timestamp'])) {

    $yes_count = (int) $vote['yes_count'];
    $no_count = (int) $vote['no_count'];

    $result = determineVoteResults($yes_count, $no_count);
    updateVoteStatus($result, $vote['vote_id'], $vote['proposal_id']);

    // Set notifications

    // For proposal author

    $author_id = fetchAuthorId($vote['vote_id']);

    insertVoteResultIntoNotifications($result, $vote['vote_id'], $author_id);

    // For all voters 
    insertVoteResultIntoNotifications($result, $vote['vote_id'], "voters");
  }
}
