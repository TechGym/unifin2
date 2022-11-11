<?php
include_once "notifications.func.php";

/**
 * Setting the referral cookie for a user 
 * Referral cookie is only set when user is not logged in
 **/

if (isset($_GET['ref']) && !isset($loggedInfo)) {
    $ref = $_GET['ref'];
    setcookie("ref", "$ref", time() + 3600 * 24 * 30, "/");
    header("Location: ./");
}

/**
 * Subscription change mail
 * 
 * Send mail and alter the subscription state of a user
 * @param String
 * @param String 
 * @return void
 **/
function subscription($ref, $state) {
    global $dbConnect;
    $sql = "SELECT email FROM subscriptions WHERE sub_ref ='$ref'";
    $result = $dbConnect->query($sql);
    $code = md5(uniqid());
    if ($result->num_rows == 1) {
        $email = $result->fetch_assoc()['email'];
        $date = gmdate("Y-m-d");

        // Send mail. Will need path
        // Unsubscribe 
        $sql = "UPDATE subscriptions SET status ='$state' , sub_ref = '$code' WHERE  email ='$email'";
        if ($state == 'active') {
            $sql = "UPDATE subscriptions SET status ='$state' , sub_ref = '$code' , subscription_date = '$date' WHERE  email ='$email'";
        }
        $dbConnect->query($sql);
        header("Location: ./");
    }
}

// Unsubscribing from the mail 
if (isset($_GET['unsub'])) {
    // Fetch email
    $ref = $_GET['unsub'];
    subscription($ref, "inactive");
}

// Resubscribing to the mail
if (isset($_GET['resub'])) {
    // Fetch email
    $ref = $_GET['resub'];
    subscription($ref, "active");
}

/**
 * Updating the membership of a user 
 * 
 * Updating the states of a member after he/she pays the membership fee of UNIFIN
 * @param Number
 * @param Date
 * @param String
 * @param String
 * @param String
 * 
 * @return void
 */
function updateMemberships($memberReferralsCount, $membershipDate, $referralCode, $userEmail, $referrerEmail) {
    global $dbConnect;

    if ($memberReferralsCount >= 5) {
        $sql = "UPDATE membership 
        SET status = 'pool', membership_date = '$membershipDate', referral_code= '$referralCode' 
        WHERE user_email = '$userEmail' ;";
    } else {
        if ($memberReferralsCount == 4) {
            $completed = "UPDATE membership SET completed = 'true' WHERE user_email = '$referrerEmail';";
            $dbConnect->query($completed);
        }
        $council = checkCouncilStatus();
        if ($council !== "counciMembersComplete") {
            $sql = "UPDATE membership 
            SET status = 'member', membership_date= '$membershipDate', council = '$council', referral_code= '$referralCode' 
            WHERE user_email = '$userEmail' ;";
        } else {
            $sql = "UPDATE membership 
            SET status = 'member', membership_date= '$membershipDate', council = NULL, referral_code= '$referralCode' 
            WHERE user_email = '$userEmail' ;";
        }
    }
    $dbConnect->query($sql);
}

/*
 *  Check council status
 * 
 * Fetch the total number of council members
 * If less than 5 , return 5 if less than 50 returns 50 else returns counciMembersComplete
 * 
 * @return String
*/
function checkCouncilStatus() {
    global $dbConnect;
    $sql = "SELECT user_email FROM membership WHERE council IS NOT NULL";
    $result = $dbConnect->query($sql);
    $length = $result->num_rows;
    if ($length < 5) {
        return "5";
    } elseif ($length < 50) {
        return "50";
    }
    return "counciMembersComplete";
}

/**
 * Modifying the sprints table on becoming a member
 * 
 * 1.Check the current sprint
 * 2. Check memberReferrals of current user's referrer 
 * 3. Modify tables based on results in 1 and 2 
 * 
 * @param Number
 * @param Date
 * @param Date
 * @return void
 */
function modifySprints($memberReferralsCount, $sprintStartDate, $sprintDueDate) {
    global $dbConnect;
    $sprint =  checkSprint();
    $currentSprint = fetchCurrentSprint();

    if ($sprint == "none") {
        if ($memberReferralsCount == 5) {
            $sql = "UPDATE sprints 
            SET  current_users = current_users + 1 , tokens_received = tokens_received + 25 WHERE sprint_id = '$currentSprint'";
        } else {
            $sql = "UPDATE sprints 
            SET  current_users = current_users + 1 , tokens_received = tokens_received + 100 WHERE sprint_id = '$currentSprint'";
        }
    } else {
        if ($memberReferralsCount == 5) {
            $sql = "INSERT into sprints(sprint_id, start_date , due_date , tokens_received , current_users) 
            VALUES ('$sprint' , '$sprintStartDate', '$sprintDueDate', 25,  1)";
        } else {
            $sql = "INSERT into sprints(sprint_id, start_date , due_date , tokens_received , current_users) 
            VALUES ('$sprint' , '$sprintStartDate', '$sprintDueDate', 100,  1)";
        }
    }
    $dbConnect->query($sql);
}


/** 
 * Modify tokens on becoming a member 
 * 
 * Referral receives tokens if the current user is not placed in the pool
 * Current member receives 25 tokens 
 * @param Number
 * @param Number 
 * @param String
 * @param Number
 * 
 * @return void
 */
function modifyTokens($memberReferralsCount, $referredBy, $userEmail, $userId) {
    global $dbConnect;
    if ($memberReferralsCount < 5) {
        $sql =  "UPDATE tokens SET total = total+ 75, team = team + 75 WHERE user_id = '$referredBy'";
        $dbConnect->query($sql);
    }
    $tokens = "INSERT INTO tokens (user_id ,user_email, total) VALUES ('$userId' , '$userEmail' , 25) ; ";
    $dbConnect->query($tokens);
}

/** 
 * Fetch the id of referrer
 * 
 * Fetch the email of the referrer with the referrer's id. 
 * Used for updating the completed status on membership page
 * @param Number
 * 
 * @return String
 */

function fetchReferrerEmail($user_id) {
    global  $dbConnect;
    $referred_by = "SELECT email FROM users WHERE user_id= '$user_id';";
    $result = $dbConnect->query($referred_by);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    return $result[0]['email'];
}

/** 
 * Insert Milestone notification
 * 
 * Insert a notiifcation to the notifications table if the membership of the current user makes his/her referrer make a full team 
 * @param String
 * @param Number
 * 
 * @return void
 */

function insertMilestoneNotification($referred_by, $date) {
    global $dbConnect;
    $type = 'milestone';
    $amount = '5';
    $category = 'finished';
    $link = "./referral";

    $sql = "INSERT INTO notifications(user_id , type ,amount, category , timestamp , link , status)
    VALUES('$referred_by' , '$type' ,'$amount', '$category' , '$date' , '$link' , 'unread')";
    $dbConnect->query($sql);
}



/*
1. Set variables from the global loggedUser
2. Fetch member referrals of current user's referrer
3. Generate loggedIn user's referral code to be used to refer other people
4. Update membership
5 . Check current sprint and update sprint 
NB: Only member referrals can be in councils
*/
function becomeAMember() {
    global $loggedInfo;
    $user_id = $loggedInfo["user_id"];
    $email = $loggedInfo["email"];
    $date = gmdate("Y-m-d H:i:s");
    $dueDate = strtotime($date) + (3600 * 24 * 30);
    $dueDate = gmdate("Y-m-d H:i:s", $dueDate);

    $referred_by = fetchReferrer($email);

    // The system do not have an email so will have a static email value of 0 
    // Also the referrerEmail is used to update the completed status of member which is not applicable to system
    if ($referred_by != '0') {
        $referrerEmail = fetchReferrerEmail($referred_by);
    } else {
        $referrerEmail = $referred_by;
    }


    // If the referral is system set $memberReferralsCount to 5

    // This sets members without referral code into the pool
    $memberReferralsCount = count(fetchMemberReferrals($referred_by));
    if ($referred_by == 0) {
        $memberReferralsCount = 5;
    }

    $referral_code = generateKey();

    updateMemberships($memberReferralsCount, $date, $referral_code, $email, $referrerEmail);

    modifySprints($memberReferralsCount, $date, $dueDate);

    modifyTokens($memberReferralsCount, $referred_by, $email, $user_id);

    // Inserting transaction for member
    insertTransaction($user_id, '25', 'system', 'membership');

    // Inserting transaction for referrer
    if ($memberReferralsCount < 5) {
        insertTransaction($referred_by, '75', 'system', 'membership');
    }

    // Check if this starts a new sprint. If so send out notification to users
    $sprint = checkSprint();
    if ($sprint != "none") {
        insertNewSprintNotification($sprint);
    }


    if ($referred_by != "0") {

        if ($memberReferralsCount < 4) {
            // Send notification to tell user's referrer that he has received a new referral
            insertMemberNotification($referred_by, "new", 1, "referral");

            // Send notification to user's referral that he has received 75 tokens 
            insertReceivedTokens(75, "referral", $referred_by);
        } else if ($memberReferralsCount ==  4) {
            // Send notification to tell user's that he has reached the milestone
            insertMilestoneNotification($referred_by, $date);
        } else {
            // Send notification to tell user's referrer that he has received a new referral
            insertMemberNotification($referred_by, "new", 1, "pool");
        }
    }
    // Send notification to user that he has received 25 tokens 
    insertReceivedTokens(25, "membership", $user_id);

    echo "success";
}
