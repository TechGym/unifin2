<?php
include("../includes/functions.php");
include("../phpMailer.php");

/*
1. Fetch data
2. Fetch email to see if emailis already registered
3. Send a mail for inform user of their registration
4 . Insert user

*/
if (isset($_POST["subscribed"])) {
    //  1
    $email = htmlspecialchars($_POST["email"]);
    $date = htmlspecialchars(gmdate("Y-m-d"));
    // 2
    $sql = "SELECT (email) FROM subscriptions WHERE email ='$email' ";
    $result = $dbConnect->query($sql);
    if ($result->num_rows == 1) {
        echo "email registered";
        return;
    }
    // 3
    $ref = generateKey();
    $path = "../mail/subscription.php";
    // $mailSend = sendSubscriptionMail($email , $path , $ref);
    $mailSend = "sent";
    if ($mailSend == "sent") {
        //  4

        $sql  = "INSERT into subscriptions (email , subscription_date , sub_ref) VALUES ('$email' , '$date' , '$ref')";
        $result = $dbConnect->query($sql);
        if ($result) {
            echo "success";
        }
    } else {
        echo $mailSend;
    }
}
