<?php

/**
 * Fetch bonus referrals 
 * 
 * Fetch all the member referrals of a user after he/she has a solid team 
 * @param Number
 * @return Array
 **/
function fetchBonusReferrals($id) {
  global $dbConnect;
  $fetch = "SELECT * FROM membership WHERE status='member' && assigned_from = '$id' ";
  $result = $dbConnect->query($fetch);
  $result = $result->fetch_all(MYSQLI_ASSOC);
  return $result;
}
/**
 * Check the time remaining for a sprint to  be over 
 * 
 * @param Number 
 * @return Number 
 **/
function checkRemainingSprintTime($currentSprint) {
  global $dbConnect;
  $sql = "SELECT start_date , due_date FROM sprints WHERE sprint_id = '$currentSprint'";
  $result = $dbConnect->query($sql);
  $result = $result->fetch_assoc();
  $remainingTime = strtotime($result['due_date']) - strtotime(gmdate("Y-m-d H:i:s"));
  $remainingTime = floor($remainingTime / (3600 * 24));

  return $remainingTime;
}
