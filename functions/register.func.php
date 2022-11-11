<?php

function checkEmptyFields($firstName, $lastName, $email, $password, $country, $date) {
    $result = false;
    if (empty($firstName || empty($lastName) || empty($email)) || empty($country) || empty($password) || empty($date)) {
        $result = "Please fil in all required credentials";
        // Can't validate state and adress for users. 
        // Worst case scenario is they won't have a value inserted 
    } else {
        if (strlen($password) < 8) {
            $result = "Password too short";
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result = "Enter a valid email address";
            }
        }
    }
    return $result;
}

// Finding the id of the user who referred someone (Used during registration to verify the referrer)
function fetchReferredBy($key) {
    global  $dbConnect;
    $sql = "SELECT u.user_id 
    FROM membership m 
    JOIN users u
    ON u.email = m.user_email
    WHERE m.referral_code = '$key'; ";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all((MYSQLI_ASSOC));
    return $result[0]['user_id'];
}

function findReferral() {
    if (isset($_COOKIE["ref"])) {
        $referralCode = $_COOKIE["ref"];
        if (fetchReferredBy($referralCode)) {
            return fetchReferredBy($referralCode);
        };
        return 0;
    }
    return 0;
}

function sendVerificationEmail($email, $email_ref) {
    // Sending the mail 

    $to = $email;
    $path = "./mail/registerVerification.php";
    return emailVerificationMail($to, $email_ref, $path);
}

function checkEmail($email) {
    global $dbConnect;
    $sql = "SELECT email FROM users WHERE email = '$email';";
    $result = $dbConnect->query($sql);
    if ($result->num_rows > 0) {
        return "Email has been used";
    }
    return true;
}

function insertIntoVerifications($email, $email_ref) {
    global $dbConnect;
    $verifications = "INSERT INTO verifications (email, email_ref) values ('$email', '$email_ref')";
    return $dbConnect->query($verifications);
}

function insertReferral($email, $referred_by) {
    global $dbConnect;
    $referral = "INSERT INTO membership (user_email , referred_by ) values ('$email' ,'$referred_by' )";
    return $dbConnect->query($referral);
}

function insertUser($firstName, $lastName, $email, $password, $country, $city, $state, $address, $date, $dob, $othernames) {
    global $dbConnect;
    if ($othernames !== "") {
        if ($state != "" && $city != "" && $address != "") {
            $user = "INSERT INTO users (firstname , lastname , email , password , joined_date , country , state , city , address , dob , othernames) values ('$firstName' ,'$lastName', '$email',  '$password'  , '$date' , '$country', '$state' ,'$city', '$address' , '$dob' , '$othernames') ";
        } else {
            $user = "INSERT INTO users (firstname , lastname , email , password , joined_date , country, dob , othernames) values ('$firstName' ,'$lastName', '$email' , '$password'  , '$date' , '$country' , '$dob' , '$othernames')";
        }
    } else {
        if ($state != "" && $city != "" && $address != "") {
            $user = "INSERT INTO users (firstname , lastname , email , password , joined_date , country , state , city , address , dob , othernames) values ('$firstName' ,'$lastName', '$email',  '$password'  , '$date' , '$country', '$state' ,'$city', '$address' , '$dob' , NULL) ";
        } else {
            $user = "INSERT INTO users (firstname , lastname , email , password , joined_date , country , dob, othernames) values ('$firstName' ,'$lastName', '$email' , '$password'  , '$date' , '$country' , '$dob' , NULL)";
        }
    }

    return $dbConnect->query($user);
    // return $user;
}
