<?php
include "config.php";

$current = 0;

// GENERAL FUNCTIONALITIES 
// Getting userInfo
if (isset($_COOKIE[md5('logUser')])) {
    $logUser = $_COOKIE[md5('logUser')];
} elseif (isset($_SESSION[md5('logUser')])) {
    $logUser = $_SESSION[md5('logUser')];
}


function navigationClass() {
    global $loggedInfo;
    $exceptions = ['home', "faq", 'contact', 'dashboard', 'map'];
    $currentURL = trim($_SERVER['REQUEST_URI']);
    $currentURL = explode("/", $currentURL);
    $currentURL = strtolower($currentURL[count($currentURL) - 1]);
    if (isset($loggedInfo) && !in_array($currentURL, $exceptions)) {
        return 'logged';
    } else {
        return 'guest';
    }
}

// Setting loggedInfo with cookie value and clearing the cookie value incases where a cookie contradicts our cookie
if (isset($logUser)) {
    // Joining users with cookies
    $sql = "SELECT u.user_id,u.firstname, u.othernames, u.title , u.lastname, u.email,u.zipcode, u.state, u.city, u.country,u.joined_date, u.address, u.dob, u.phone , u.password
    FROM users u
    JOIN cookies c
    ON u.email = c.email
    WHERE  c.cookie = '$logUser'";
    $result = $dbConnect->query($sql);
    if ($result->num_rows < 1) {
        // clear cookies 
        setcookie(md5('logUser'), "", time() - (24 * 3600), "/");
        unset($_SESSION);
    } else {
        $loggedInfo = $result->fetch_all(MYSQLI_ASSOC);
        $loggedInfo = $loggedInfo[0];
    }
}

function padId($id) {
    for ($i = strlen($id); $i < 6; $i++) {
        $id = "0" . $id;
    }
    return $id;
}
// Some useful variables.
if (isset($loggedInfo)) {
    $status = fetchStatus($loggedInfo['email']);
    $currentSprint = (int)fetchCurrentSprint();
    if ($status != "pending") {
        $currentBalance = fetchBalance()["total"];
    }
}

// Fetching User's status
function fetchStatus($memberEmail) {
    global  $dbConnect;
    $sql = "SELECT status FROM membership WHERE user_email= '$memberEmail'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    $result = $result[0]['status'];
    return $result;
}

// Fetching User's membership date 
function fetchMembershipDate($memberEmail) {
    global  $dbConnect;
    $sql = "SELECT membership_date FROM membership WHERE user_email= '$memberEmail'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    $result = $result[0]['membership_date'];
    return $result;
}

// Fetching the balance of the logged in user
function fetchBalance() {
    global  $dbConnect, $loggedInfo;
    $userId = $loggedInfo['user_id'];
    $status = fetchStatus(($loggedInfo['email']));
    if ($status == 'pool' || $status == 'member') {
        $balance = "SELECT total, team , awards , tips , deductions FROM tokens WHERE user_id='$userId'";
        $balance = $dbConnect->query($balance);
        $balance = $balance->fetch_all((MYSQLI_ASSOC));
        return $balance[0];
    }
}
// Fetches ip address of current to used in sending detailEdit emails and others 
function fetchIp() {
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Prevents the logged in user from seeing some pages if user is not a member yet
function statusRedirect() {
    global $loggedInfo;
    $status = fetchStatus(($loggedInfo['email']));
    if ($status != 'pool' && $status != 'member') {
        header("Location: ./home");
    }
}

// Fetch the referrer of the person who referred the logged in user.
// Used during become a member to get the referrrer of the logged in user 
function fetchReferrer($userEmail) {
    global  $dbConnect;
    $referred_by = "SELECT referred_by FROM membership WHERE user_email= '$userEmail';";
    $result = $dbConnect->query($referred_by);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    return $result[0]['referred_by'];
}

// Get the total number of member referrals of a particular user (value from fetchReferrer() )
// Used during becoming a member to specify pool or member referrals
function fetchMemberReferrals($referrerId) {
    global  $dbConnect;
    $sql = "SELECT user_email , id FROM membership WHERE referred_by= '$referrerId' && status = 'member'
     ;";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    return $result;
}

// For finding the current sprint we are in
function fetchCurrentSprint() {
    global  $dbConnect;
    $sql = "SELECT sprint_id FROM sprints ORDER BY sprint_id";
    $result = $dbConnect->query($sql);
    return $result->num_rows;
}

function fetchTotalMembers() {
    global $dbConnect;
    $fetch = "SELECT * FROM membership WHERE status='member' || status = 'pool'; ";
    $result = $dbConnect->query($fetch);
    $result = $result->num_rows;
    return $result;
}


/*
1. Set sprints based on the totalMembers in the app
2. if result is none, it means the sprint hasn't ended hence update sprint 
3. If result isn't none the current sprint has ended and the next sprint is created
NB: checkSprint() is run after the membership insert in becomeMember() 
    hence it counts the current user  as part of the members in the fetchTotalMembers()
    a change ins print depends on the current user 
*/
function checkSprint() {
    $num = fetchTotalMembers();

    if ($num == 1) {
        return 1;
    }
    if ($num == 51) {
        return 2;
    }
    if ($num == 251) {
        return 3;
    }
    if ($num == 1251) {
        return 4;
    }
    if ($num == 6251) {
        return 5;
    }
    if ($num == 31251) {
        return 6;
    }
    if ($num == 156251) {
        return 7;
    }
    if ($num == 781251) {
        return 8;
    } else {
    }
    if ($num == 3906251) {
        return 9;
    }
    // Ten to realise all sprints have ended 
    if ($num == 19581251) {
        return 10;
    }

    return "none";
}

/* 
Fetches the fullname from a passed in email 
Used in profile, referral and proposal pages 
*/
function fetchFullName($email) {
    global $dbConnect;
    $sql = "SELECT title, firstname , lastname , othernames FROM users WHERE email = '$email';";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all((MYSQLI_ASSOC));
    $result = $result[0];

    $fullName = "";
    if (isset($result['title'])) {
        $fullName .= ucwords($result['title']);
    }
    $fullName .= " " . $result["firstname"];
    if (isset($result["othernames"])) {
        // Find initials
        $middle = explode(" ", $result['othernames']);
        $init = "";
        foreach ($middle as $mid) {
            $init .= $mid[0] . ". ";
        }
        $fullName .= " " . $init;
    }
    $fullName = $fullName . " " . $result["lastname"];
    return $fullName;
}


function fetchPhoneStatus($email) {
    global $dbConnect;
    $sql = "SELECT phone_status FROM verifications WHERE email = '$email'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    return $result[0]['phone_status'];
}

// For verifying the input password
function matchPassword($email, $password) {
    global $dbConnect;
    $sql = "SELECT password FROM users WHERE email = '$email' && password = '$password'";
    $password = $dbConnect->query($sql);
    return $password->num_rows;
}


function generateKey() {
    $key = "";
    $alp = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    for ($i = 0; $i < 32; $i++) {
        $key .= $alp[rand(0, strlen($alp) - 1)];
    }
    return $key;
}

function sendSMS($email, $phone) {
    global $dbConnect;
    $key = "";
    $num = "1234567890";
    for ($i = 0; $i < 6; $i++) {
        $key .= $num[rand(0, strlen($num) - 1)];
    }
    // Updating phone_ref
    $sql = "UPDATE verifications SET phone_ref='$key' WHERE email = '$email'";
    $result = $dbConnect->query($sql);
    if ($result) {
        return "phoneSuccess";
    }
    return "phoneError";
    // Sending SMS
}

// Returns the council of user
function checkUsersCouncil($email) {
    global $dbConnect;
    $sql = "SELECT council FROM membership WHERE user_email ='$email'";
    $council = $dbConnect->query(($sql));
    $council = $council->fetch_assoc();
    return $council['council'];
}

// For preserving white spaces inputted in  input fields
function preserveWhiteSpaces($string) {
    $string = preg_replace('#(\\\r\\\n|\\\n)#', "<br>", $string);
    return $string;
}

function truncateText($text, $limit) {
    if (strlen($text) > $limit) {
        return substr($text, 0, $limit) . "...";
    }
    return $text;
}

// Used for determining the color codes of proposals and votes
function determineStatus($proposal_status, $proposal_id) {
    if ($proposal_status == "approved") {
        global $dbConnect;
        $sql = "SELECT status FROM votes WHERE proposal_id = '$proposal_id'";
        $result = $dbConnect->query($sql);
        $result = $result->fetch_assoc()['status'];
        if ($result == 'rejected') {
            return 'disapproved';
        }
        return $result;
    } elseif ($proposal_status == "rejected") {
        return 'disapproved';
    }
    return $proposal_status;
    // If approved get the status by fetching data from votes 
}


function preserveFormValue($value) {
    if (isset($_POST[$value])) {
        return $_POST[$value];
    }
    return "";
}

function formatDate($date) {
    $arr = explode("-", $date);
    $arr = [$arr[1], $arr[2], $arr[0]];
    return ucwords(join("-", $arr));
}
// function determineIconName($status)
// {
//     if ($status == "active") {
//         return "fa-spinner";
//     }
//     if ($status == "disapproved") {
//         return "fa-thumbs-down";
//     }
//     if ($status == "approved") {
//         return "fa-thumbs-up";
//     }
//     if ($status == "pending") {
//         return "fa-question";
//     }
// }

function loggedUserCountryFlag() {
    global $loggedInfo, $dbConnect;
    $country = $loggedInfo['country'];
    $sql = "SELECT link_to_flag FROM countries WHERE country_name = '$country'";
    $stmt = $dbConnect->query($sql);
    return $stmt->fetch_assoc()['link_to_flag'];
}


function setModStatus() {
    global $loggedInfo;
    $status = checkUsersCouncil($loggedInfo['email']);
    return $status == "5" || $status == "50" ? true : false;
}

function padNumber($number) {
    return $number > 10 ? $number : "0" . $number;
}

function formatTime($time) {
    $time = explode(":", $time);
    $hour = (int) $time[0];
    $minutes = $time[1];
    $seconds = $time[2];
    if ($hour > 12) {
        if ($hour - 12 < 10) {
            return padNumber($hour - 12) . ":" . $minutes . ":" . $seconds . " PM";
        }
        return $hour - 12 . ":" . $minutes . ":" . $seconds . " PM";
    }
    if ($hour === 12) {
        return $hour .  ":" . $minutes . ":" . $seconds . " PM";
    }
    return $hour .  ":" . $minutes . ":" . $seconds . " AM";
}

function createDOB($dob) {
    $pattern = '/-/';
    $dob = (preg_replace($pattern, "/", $dob));
    $dob = strtotime($dob);
    return date("Y-m-d", $dob);
}



function createDecimalPlaces($num) {
    if (strpos($num, ".")) {
        $arr = explode(".", $num);
        $dp = substr($arr[1], 0, 2);
        return $arr[0] . "." . $dp;
    } else {
        return $num;
    }
}
function createSprintPercentage() {
    $endPoints = [1, 51, 251, 1251, 6251, 31251, 156251, 781251, 3906251, 19531251];
    $totalMembers =  fetchTotalMembers();
    $filt =  array_filter($endPoints, function ($elem) use ($totalMembers) {
        return $elem > (int) $totalMembers;
    });
    // Getting the upper limit in the filter
    $upper = array_values($filt)[0];
    // Getting the index of the upper limit
    $index = array_search($upper, $endPoints);
    $lower = array_values($endPoints)[$index - 1];
    $diff_lower_members = $totalMembers - $lower + 1;
    $diff_lower_upper = $upper - $lower;
    $num = ($diff_lower_members / $diff_lower_upper) * 100;

    // Grabbing the first 2 dp  if any
    $percentageComplete = createDecimalPlaces($num);

    return $percentageComplete . "%";
}

function checkCouncilOf5($email) {
    $council = checkUsersCouncil($email);
    if ($council === "5") {
        return true;
    }
    return false;
}

function insertTransaction($target, $amount, $sender, $type) {
    global $dbConnect;
    $timestamp = gmdate("Y-m-d H:i:s");
    $sql = "INSERT into transactions (amount , receiver , sender_email, type , timestamp ) VALUES ('$amount' , '$target' , '$sender' , '$type' , '$timestamp' );";
    return $dbConnect->query($sql);
}
