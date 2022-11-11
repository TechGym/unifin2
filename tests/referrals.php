<?php
include "../includes/functions.php";

$countries = ["Ghana", "Togo", "China", "Argentina", "Cameroon", "Mali"];
$firstnames = ["Asare", "Solomon", "Steve", "Him"];

function createReferrals($i)
{
    global $countries, $dbConnect, $firstnames;
    $firstname = $firstnames[rand(0, count($firstnames) - 1)] . $i;
    $lastname = "Dead";
    $email = $firstname . $lastname . "@gmail.com";
    $password = md5($firstname . $lastname);
    $date = gmdate("Y-m-d:h-i-s");
    $country = $countries[rand(0, count($countries) - 1)];
    $referrerId = 2;
    // Inserting into verifications
    $verifications =
        "INSERT INTO verifications (email) values ('$email')";
    $verifications = $dbConnect->query($verifications);
    if ($verifications) {
        echo "1";
    }
    // Inserting the referrer
    $insert = "INSERT INTO referrals (user_email , referred_by ) values ('$email' ,'$referrerId')";
    $insert = $dbConnect->query($insert);
    if ($insert) {
        echo "2";
    }
    // Inserting into users
    $fill = "INSERT INTO users (firstname , lastname , email , password , joined_date, country) values ('$firstname' ,'$lastname', '$email' , '$password'  , '$date' , '$country')";
    $result = $dbConnect->query($fill);
    if ($result) {
        echo "3";
    }
}
$dead = 0;
if (isset($dead)) {
    for ($i = 1; $i < 2; $i++) {
        createReferrals($i);
    }
}
