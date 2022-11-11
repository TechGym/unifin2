<?php
include "../includes/functions.php";
include "../phpMailer.php";

/*
 If report, 
1. Fetch values 
2. Send the mail
3. Create return messages. Though message sending failed is not expected
*/
if (isset($_POST['report'])) {
    // 1
    $heading = trim(htmlspecialchars($_POST["heading"]));
    $message = trim(htmlspecialchars($_POST["message"]));
    $email = $loggedInfo["email"];

    // 2 
    //     $mailSend = emailSupport($email, $message , $heading);
    $mailSend = "sent";
    // 3 
    if ($mailSend == "sent") {
        echo "report";
    } else {
        echo  "Message sending failed";
    }
}

/*
 If contact, 
1. Fetch values  and validate them
2. Handle Errors
3. Send the mail
4. Create return messages. 
Though message sending failed is not expected
4, Insert into database
*/
if (isset($_POST['contact'])) {
    // 1
    $heading = "Contact message from " . $_POST["firstname"];
    $message = trim(htmlspecialchars($_POST["message"]));
    $email = strtolower(trim(htmlspecialchars($_POST["email"])));
    $firstname = ucwords(trim(htmlspecialchars($_POST["firstname"])));
    $lastname = ucwords(trim(htmlspecialchars($_POST["lastname"])));
    $organization = ucwords(trim(htmlspecialchars($_POST["organization"])));
    $date = gmdate("Y-m-d");
    $path = "../mail/contact.php";

    $message = mysqli_real_escape_string($dbConnect, $message);
    $message = preserveWhiteSpaces($message);

    // 2
    if (empty($message) || empty($email) || empty($firstname) || empty($organization) || empty($lastname)) {
        echo "Please fill in all credentials";
        return;
    }
    if (!preg_match("/^[a-zA-Z]+$/", $firstname) || !preg_match("/^[a-zA-Z]+$/", $lastname)) {
        echo "Please use valid name formats";
        return;
    }
    if ($organization == "") {
        $organization = "Personal";
    }
    // 3
    // $mailSend = contactSupport($email, $path, $heading, $firstname, $organization, $email, $heading, $message);
    $mailSend = 'sent';
    if ($mailSend == "sent") {
        //  4
        $sql = "INSERT into contacts (firstname , lastname , email , organization , heading , message , date)  
        VALUES('$firstname' , '$lastname' , '$email' , '$organization' , '$heading' , '$message' , '$date')";

        $contact = $dbConnect->query($sql);
        if ($contact) {
            echo "contact";
        } else {
            echo "An error occured ";
            return;
        }
    }
}
