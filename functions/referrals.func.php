<?php
/* 
1. Total referrals count in functions.php file
2 . Display referrals
*/

// Fetching member referrals
$referrals = fetchMemberReferrals($loggedInfo['user_id']);
$totalReferrals = count($referrals);

/**
 * Display total Referrals 
 * 
 * Fetch member referrals and using its value; dispaly referrals count. Format number/5
 */
function displayReferrals() {
    global $totalReferrals;
    return $totalReferrals  . "/5";
}

/**
 * Get remaining time
 * 
 * Get the remaining time (max of 30)  left for loggedIn user to refer member referrals 
 * @return Number
 */
function getRemainingTime() {
    global $dbConnect, $loggedInfo;
    $userEmail = $loggedInfo['email'];
    $sql = "SELECT membership_date FROM membership WHERE user_email = '$userEmail'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_assoc();
    $dueTime = strtotime($result['membership_date']) + (3600 * 24 * 30);
    $currentTime =  strtotime(gmdate("Y-m-d H:i:s"));
    $remainingTime =  floor(($dueTime - $currentTime) / (3600 * 24));
    return $remainingTime;
}

/**
 * Fetch pendingReferrals of loggedIn User based on the numbr of member referrals left
 * @param Number
 * @return String[]
 */


function fetchPendingUsers($limit) {
    global  $dbConnect, $loggedInfo;
    $userId = $loggedInfo['user_id'];
    $sql = "SELECT user_email , id FROM membership WHERE referred_by= '$userId' && status = 'pending' LIMIT $limit;";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    return $result;
}

// Fetch member referrals if they are less than 5 fetch pending referrals (5 - totalMembers) and merge the two
function mergeReferrals() {
    global $totalReferrals, $loggedInfo, $referrals;
    if ($totalReferrals < 5) {
        $pendingReferrals = fetchPendingUsers(5 - $totalReferrals);
        $referrals = [...$referrals, ...$pendingReferrals];
    }
    return $referrals;
}

/**
 * Fetch referral code of loggedIn user
 * @return String
 */
function fetchReferralCode() {
    global  $dbConnect, $loggedInfo;
    $userEmail = $loggedInfo['email'];
    $sql = "SELECT referral_code FROM membership WHERE user_email = '$userEmail' ; ";
    $code = $dbConnect->query($sql);
    $code = $code->fetch_all((MYSQLI_ASSOC));
    return $code[0]["referral_code"];
}
