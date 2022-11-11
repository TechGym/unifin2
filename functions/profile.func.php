<?php

/** 
 * Create Prev country data
 * 
 * Fetches data and creates a string about the current country data of a loggedin user before the user changes his/her country data
 * 
 * return String
 */
function createPrevCountryData() {
  global $loggedInfo, $dbConnect;
  if (isset($loggedInfo['state'])) {
    $prevData = $loggedInfo['country'] . "<br>";
    $prevData .= $loggedInfo['state'] . "<br>";
    $prevData .= $loggedInfo['city'] . "<br>";
    $prevData .= $loggedInfo['address'];
    return $prevData;
  }
  return $loggedInfo['country'];
}

/** 
 * Create Prev fullname data
 * 
 * Fetches data and creates a string about the current name data of a loggedin user before the user changes his/her  name  data
 * 
 * return String
 */
function createPrevNameData() {
  global $loggedInfo;
  $prevData = $loggedInfo['firstname'] . "<br>";
  $prevData .= $loggedInfo['othernames'] . "<br>";
  $prevData .= $loggedInfo['lastname'] . "<br>";
  if (isset($loggedInfo['title'])) {
    $prevData = $loggedInfo['title'] . "<br>" . $prevData;
  }
  return $prevData;
}



/** 
 * Create New country data
 * 
 * Creates a string with the data of the loggedin user when he/she is about changeing his/her  country  data
 * 
 * return String
 */
function createNewCountryData($country, $state, $address, $city) {
  if ($state != "") {
    $newData = $country . "<br>";
    $newData .= $state . "<br>";
    $newData .= $city . "<br>";
    $newData .= $address;
    return $newData;
  }
  return $country;
}

/** 
 * Create New name data
 * 
 * Creates a string with the data of the loggedin user when he/she is about changeing his/her  name  data
 * 
 * return String
 */
function createNewNameData($title, $firstname,  $othernames, $lastname) {
  $newData = $title . "<br>";
  $newData .= $firstname . "<br>";
  $newData .= $othernames . "<br>";
  $newData .= $lastname . "<br>";
  return $newData;
}

/**
 * Fetch the proposals of a logged in user  
 * 
 * Only avaibale for council members
 * @return String[]
 */
function fetchLoggedUsersProposals() {
  global $loggedInfo, $dbConnect;
  // Fetching proposals
  $id = $loggedInfo['user_id'];
  $sql = " SELECT * FROM proposals WHERE  show_proposal ='true' && author_id = '$id' LIMIT 4";
  $proposals = $dbConnect->query($sql);
  $proposals = $proposals->fetch_all(MYSQLI_ASSOC);
  return $proposals;
}

/**
 * Fetch the verifications Status of a logged in user
 * To be used for displaying "Add" button on profile page
 */
function fetchVerificationsStatus() {
  global $loggedInfo, $dbConnect;
  $email = $loggedInfo['email'];
  $sql = "SELECT * FROM verifications WHERE email = '$email';";
  $verifications = $dbConnect->query($sql);
  $verifications = $verifications->fetch_all(MYSQLI_ASSOC);
  $verifications = $verifications[0];
  return $verifications;
}
