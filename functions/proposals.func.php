<?php

/**
 * Fetches all active proposals
 * 
 * All proposals that has a status of pending
 * @return String[]
 */
function fetchActiveProposals() {
  global $dbConnect;
  // Count active proposals
  $total = "SELECT * FROM proposals WHERE status = 'pending' ORDER BY timestamp DESC;";
  $result = $dbConnect->query($total);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}
/**
 * Fetches all moderated proposals(Ones whoses status is not pending)
 * @return String[]
 */
function fetchModeratedProposals($limit) {
  global $dbConnect;
  $total = "SELECT * FROM proposals WHERE status NOT IN ('pending') ORDER BY timestamp DESC LIMIT $limit, 10;";
  $result = $dbConnect->query($total);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function checkIfUserIsACouncilOf5Member() {
  global $dbConnect, $loggedInfo;
  $email = $loggedInfo['email'];
  $total = "SELECT * FROM membership WHERE user_email = '$email' &&  council = '5'";
  $result = $dbConnect->query($total);
  $result = $result->fetch_assoc();
  return $result;
}


/**
 * Fetch a proposal with id
 * 
 * Fetches a proposal from the proposlas table with its id
 * @param Number
 * @return String[]
 */
function fetchProposalWithId($id) {
  global $dbConnect;
  $sql = "SELECT * FROM proposals WHERE proposal_id = '$id'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  return $result;
}

/**
 * Fetch an author with id
 * 
 * Fetches a user from the proposlas table with the users id(used for authors of a proposal)
 * @param Number
 * @return String[]
 */
function fetchAuthorWithId($id) {
  global $dbConnect;
  $sql = "SELECT  email FROM users WHERE user_id = '$id'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  return $result;
}

/**
 * Check moderator
 * 
 * Check if a user has already moderated a proposal or not 
 * Fetch moderators of a particular proposal
 * Iterate through and match it with the id of the loggedin user 
 * if it matches with any or not return a result 
 * 
 * @param Number 
 * @return String
 */
function checkModerator($proposal_id) {
  global $dbConnect, $loggedInfo;
  $sql = "SELECT approvers, rejectors FROM proposals WHERE proposal_id = '$proposal_id'";
  $result = $dbConnect->query($sql);
  $id = $loggedInfo["user_id"];
  $result = $result->fetch_assoc();
  if ($result) {
    $approvers =  $result['approvers'];
    $rejectors = $result['rejectors'];
    $moderators = $approvers . " " . $rejectors;
    $moderators = explode(" ", $moderators);
    if (in_array($id, $moderators)) {
      return "true";
    }
    return "false";
  }
  return 'false';
}
/**
 * Fetch proposal rejection codes
 * 
 * Fetch rejection codes of a rejected proposal 
 * Explode result into an array and find unique elements
 * Sort rejection_codes in ascending order
 * 
 * @param Number 
 * @return String[]
 */
function fetchProposalRejectionCodes($proposal_id) {
  global $dbConnect;
  $sql = "SELECT rejection_codes FROM proposals WHERE proposal_id = '$proposal_id'";
  $result = $dbConnect->query($sql);
  $result = trim($result->fetch_assoc()['rejection_codes']);
  $rejection_codes = explode(" ", $result);
  $unique_codes = array_unique($rejection_codes);
  $sorted_codes = sort($unique_codes);
  return ($unique_codes);
}

/**
 * Fetch rejection codes from the rejection_codes table 
 * @return String[]
 */
function fetchRejectionCodes() {
  global $dbConnect;
  $sql = "SELECT * FROM rejection_codes";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}

/**
 * Fetch a rejection reason with a rejection code from the rejection_codes table 
 * @param Number
 * @return String
 */
function fetchRejectionWithCode($rejection_code) {
  global $dbConnect;
  if (!empty($rejection_code)) {
    $sql = "SELECT rejection FROM rejection_codes WHERE rejection_id = '$rejection_code'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_assoc();
    return $result['rejection'];
  }
}
/**
 * Fetch author's latest proposal
 * 
 * Fetches the proposal id of the currently created proposal(creating a proposal)
 * @param Number
 * @return String
 */
function fetchAuthorsLatestProposal($authorId) {
  global $dbConnect;
  $sql = "SELECT proposal_id FROM proposals WHERE author_id = '$authorId' ORDER BY timestamp DESC";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  return $result['proposal_id'];
}

/**
 * Fetch all council members 
 * 
 * @return String[]
 */
function fetchAllCouncilMembers() {
  global $dbConnect;
  $sql = "SELECT u.user_id 
    FROM membership m 
    JOIN users u 
    ON u.email = m.user_email
    WHERE m.council = '5' || m.council = '50' ";
  $council = $dbConnect->query(($sql));
  $council = $council->fetch_all(MYSQLI_ASSOC);
  $members = '';
  foreach ($council as $member) {
    $userId = $member['user_id'];
    $members .=  $userId . " ";
  }
  return trim($members);
}


/**
 * Preserve line breaks 
 * 
 * Preserve line breaks ("<br>' tags) when displaying a proposal
 * 
 * @param String
 * @return String
 */
//  For swapping out <br> tags into HTML entities to preserve line breaks in the formField
function preserveLineBreaksInEdit($text) {
  $text = preg_replace("/\s+/", " ", $text);
  $text = preg_replace("/<br>/ ", "&#13&#10", $text);
  return trim($text);
}
