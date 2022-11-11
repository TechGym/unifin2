<?php
function checkUserVoteStatus($userId, $voteId) {
  global $dbConnect;
  $sql = "SELECT voters FROM votes WHERE vote_id = '$voteId'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  if ($result) {
    $voters = $result['voters'];
    $voters = explode(" ", $voters);

    $found = false;
    foreach ($voters as $voter) {
      if ($voter == $userId) {
        $found = true;
      }
    }
    return $found;
  }
  return false;
}
function fetchActiveVotes() {
  global $dbConnect;
  // Count active proposals
  $total = "SELECT * , v.timestamp
FROM votes v
JOIN proposals p
ON v.proposal_id = p.proposal_id
WHERE v.status = 'active' ORDER BY v.timestamp;";
  $result = $dbConnect->query($total);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function checkVoter($vote_id) {
  global $dbConnect, $loggedInfo;
  $sql = "SELECT voters FROM votes WHERE vote_id = '$vote_id'";
  $result = $dbConnect->query($sql);
  $id = $loggedInfo["user_id"];
  $result = $result->fetch_assoc();
  if ($result) {
    $voters = $result['voters'];
    $voters = explode(" ", $voters);
    if (in_array($id, $voters)) {
      return "true";
    }
    return "false";
  }
  return 'false';
}

function fetchProposalWithVoteId($voteId) {
  global $dbConnect;
  $sql = "SELECT proposal_id FROM votes WHERE vote_id = '$voteId'; ";
  $result = $dbConnect->query($sql);
  if ($result->num_rows === 0) {
    return false;
  }
  $proposal_id =  $result->fetch_assoc()['proposal_id'];

  // Fetching proposal
  $sql = "SELECT * FROM proposals WHERE proposal_id = '$proposal_id' ; ";
  $result = $dbConnect->query($sql);
  if ($result->num_rows === 0) {
    return false;
  }
  return $result->fetch_assoc();
}

function fetchVoteWithId($voteId) {
  global $dbConnect;
  $sql = "SELECT * FROM votes WHERE vote_id = '$voteId'; ";
  $result = $dbConnect->query($sql);
  return $result->fetch_assoc();
}

function fetchFinishedVotes($limit) {
  global $dbConnect;
  $total = "SELECT * 
    FROM votes v
    JOIN proposals p
    ON v.proposal_id = p.proposal_id
    WHERE v.status = 'rejected' OR v.status = 'approved'
    ORDER BY v.timestamp DESC LIMIT $limit, 10;";
  $result = $dbConnect->query($total);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}
// print_r(fetchFinishedVotes(0));

function formatNumber($number) {
  $number = (int) $number;
  if ($number < 10) {
    return "0" . $number;
  }
  return $number;
}
function checkRemainingVoteTime($proposalTime) {
  $endTime = strtotime($proposalTime) + (3600 * 72);

  $currentTime = strtotime(gmdate("Y-m-d H:i:s"));
  $remainingTime = $endTime - $currentTime;
  // Format number 
  $seconds = "";
  $days = floor($remainingTime / (60 * 60 * 24));
  $hours = floor($remainingTime / (60 * 60)) % 24;
  $minutes = floor($remainingTime / 60) % 60;
  return formatNumber($days) . ":" .  formatNumber($hours) . ":" . formatNumber($minutes);
}


// Fetch pending votes and finalized 
/*
1. FETCHING PENDING VOTES 
  I. Fetch all active votes
  II. Check if logged in user has already voted on  the vote
  III . Check voting time to see if time has elapsed if no , display vote

*/

function createActiveVotes() {
  $allActiveVotes = fetchActiveVotes();


  $activeVotes = [];
  // Check if time has elapsed and store it in active votes 
  // This is for when CRON jobs has not already modified the vote

  foreach ($allActiveVotes as $unmderatedVote) {
    $current = strtotime(gmdate("Y-m-d H:i:s"));
    $time = strtotime($unmderatedVote['timestamp']) + (3600 * 72);
    if ($time - $current >   0) {
      array_push($activeVotes, $unmderatedVote);
    }
  }
  return $activeVotes;
}

function createUnModeratedVotes() {
  $allActiveVotes = createActiveVotes();
  // // Check voter 
  $unmoderatedVotes = [];

  foreach ($allActiveVotes as $vote) {
    $moderator = checkVoter($vote['vote_id']);
    if ($moderator == "false") {
      $vote["mod"]  =  "unmod";
      array_push($unmoderatedVotes, $vote);
    }
  }
  return $unmoderatedVotes;
}

function createModeratedVotes() {
  $allActiveVotes = createActiveVotes();
  // // Check voter 
  $moderatedVotes = [];

  foreach ($allActiveVotes as $vote) {
    $moderator = checkVoter($vote['vote_id']);
    if ($moderator == "true") {
      $vote["mod"]  =  "mod";
      array_push($moderatedVotes, $vote);
    }
  }
  return $moderatedVotes;
}

function fetchVotesResults($vote_id) {
  global $dbConnect;
  $sql = "SELECT yes_count , no_count FROM votes WHERE vote_id = '$vote_id' ; ";
  $result = $dbConnect->query($sql);
  if ($result->num_rows === 0) {
    return false;
  }
  return $result->fetch_assoc();
}
