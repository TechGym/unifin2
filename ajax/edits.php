<?php
include "../includes/functions.php";
include "../functions/profile.func.php";
include "../phpMailer.php";

/*
NB : All edits are first validated by the edits.js and profile.js files
*/

// Edit name functionality 
/*
I. Fetch data and sanitize
II. Match password to see if user entered the correct password
III . Send email to user notifying them about the change
IV . Update username and send a success as response Text / Updates Changes table
*/
if (isset($_POST['nameForm'])) {
    // I
    $firstName = htmlspecialchars($_POST["firstname"]);
    $lastName = htmlspecialchars($_POST["lastname"]);
    $othernames  = htmlspecialchars($_POST["othernames"]);
    $title = ucwords(htmlspecialchars($_POST["title"]));
    $email = $loggedInfo['email'];
    $id = $loggedInfo["user_id"];
    $password = md5(htmlspecialchars($_POST["password"]));

    $user = $loggedInfo['firstname'];
    $ip = fetchIp();
    $date = gmdate("Y-m-d H:i:s");
    $path = "../mail/detailChange.php";
    $type = "personal informaton (full name)";

    //  II
    // Matching password
    $passwordMatch = matchPassword($email, $password);
    $prevData = createPrevNameData();
    $newData = createNewNameData($title, $firstName,  $othernames, $lastName);

    if ($prevData === $newData) {
        echo "No change in details detected";
        return;
    }


    if ($passwordMatch == 0) {
        echo "Enter your current password ";
    } else {
        // III
        // Update 
        // $mailSend = detailChangeMail($email, $path , $type , $user , $ip , $date);
        $mailSend = "sent";
        if ($mailSend == "sent") {

            //  IV
            if ($othernames == "") {
                $sql = "UPDATE users SET firstname = '$firstName', lastname = '$lastName', othernames = NULL , title ='$title'  WHERE user_id = '$id' ";
            } else {
                $sql = "UPDATE users SET firstname = '$firstName', lastname = '$lastName', othernames = '$othernames' , title ='$title' WHERE user_id = '$id' ";
            }
            $result = $dbConnect->query($sql);

            $changes = "INSERT into changes (user_id, type, ip , prev_data , new_data , timestamp) VALUES ('$id' , '$type' , '$ip' , '$prevData' , '$newData' ,'$date' )";
            $changes = $dbConnect->query($changes);
            if ($result && $changes) {
                echo 'success';
            }
        }
    }
}

if (isset($_POST['dobForm'])) {
    // I
    $dob = $_POST["dob"];
    $password = md5(htmlspecialchars($_POST["password"]));
    $email = $loggedInfo['email'];
    $id = $loggedInfo["user_id"];
    $user = $loggedInfo['firstname'];
    $ip = fetchIp();
    $date = gmdate("Y-m-d H:i:s");
    $path = "../mail/detailChange.php";
    $type = "personal informaton (dob)";

    $dob = createDOB($dob);
    //  II
    // Matching password
    $passwordMatch = matchPassword($email, $password);
    $prevData = $loggedInfo['dob'];
    $newData = $dob;


    if ($prevData === $newData) {
        echo "No change in date of birth detected";
        return;
    }


    if ($passwordMatch == 0) {
        echo "Enter your current password ";
    } else {
        // III
        // Update 
        // $mailSend = detailChangeMail($email, $path , $type , $user , $ip , $date);
        $mailSend = "sent";
        if ($mailSend == "sent") {

            //  IV
            $sql = "UPDATE users SET dob ='$dob'  WHERE user_id = '$id' ";
            $result = $dbConnect->query($sql);

            $changes = "INSERT into changes (user_id, type, ip , prev_data , new_data , timestamp) VALUES ('$id' , '$type' , '$ip' , '$prevData' , '$newData' ,'$date' )";

            $changes = $dbConnect->query($changes);
            if ($result && $changes) {
                echo 'success';
            }
        }
    }
}
// Edit phone functionality 
/*
I. Fetch data and sanitize
II. Match password to see if user entered the correct password
III . Compare phone to see if it is already registered
IV .Generate a confirmation code and send it as SMS
V . Update phone_ref and send a verifyPhone as response Text
VI. Set $_SESSION['phone']
*/
if (isset($_POST['phoneForm'])) {
    // I
    $phone = htmlspecialchars($_POST["phone"]);
    $id = $loggedInfo["user_id"];
    $email = $loggedInfo['email'];
    $password = md5(htmlspecialchars($_POST["password"]));

    // II
    $sql = "SELECT password, phone FROM users WHERE user_id = '$id' && password = '$password'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    if (count($result) == 0) {
        echo "Please enter your current password ";
    } else {
        // III
        if ($result[0]['phone'] == $phone) {
            echo 'samePhone';
            return;
        }
        // VI 
        $_SESSION["phone"] = $phone;
        //  IV 
        $code = "";
        $num = "1234567890";
        for ($i = 0; $i < 6; $i++) {
            $code .= $num[rand(0, strlen($num) - 1)];
        }
        // Send SMS and insert into database

        // V
        $sql = "UPDATE verifications SET phone_ref = '$code' WHERE email = '$email' ";
        $dbConnect->query($sql);
        echo "verifyPhone";

        // Phone will be updated after confirmation of code (verifications.php)
    }
}

// Edit address functionality 
/*
I. Fetch country name and sanitize
II. Match password to see if user entered the correct password
III . Using country name fetch more information and sanitize data(If country has states)
III . Check if the country specified is same as one already registered(for countries without states)
IV . Send email to user notifying them about the change
IV . Update address and send a success as response Text
*/
if (isset($_POST['addressForm'])) {
    // I.
    $country = htmlspecialchars($_POST["country"]);
    $id = $loggedInfo["user_id"];
    $user = fetchFullName($loggedInfo['email']);
    $ip = fetchIp();
    $date = gmdate("Y-m-d H:i:s");
    $email  = $loggedInfo['email'];
    $password = md5(htmlspecialchars($_POST["password"]));
    $path = "../mail/detailChange.php";
    $type = "personal informaton (address)";

    // II
    $sql = "SELECT password , country FROM users WHERE user_id = '$id' && password = '$password'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    if (count($result) == 0) {
        echo "Please enter your current password ";
    } else {
        // For countries  states
        // III
        if (isset($_POST["state"])) {
            $state = htmlspecialchars($_POST["state"]);
            $city = htmlspecialchars($_POST["city"]);
            $address = htmlspecialchars($_POST["address"]);
            $zipCode = htmlspecialchars($_POST["zipCode"]);

            // IV
            // $mailSent = detailChangeMail($email, $path , $type , $user , $ip , $date);
            $mailSent = "sent";
            if ($mailSent == "sent") {
                //  V
                // Update Data and Store changes 
                $sql = "UPDATE users SET country = '$country' , city = '$city' , address = '$address' , state = '$state' , zipcode = '$zipCode' WHERE user_id = '$id' ";
                $result = $dbConnect->query($sql);

                $prevData = createPrevCountryData();
                $newData = createNewCountryData($country, $state, $address, $city);

                $changes = "INSERT into changes (user_id, type, ip , prev_data , new_data , timestamp) VALUES ('$id' , '$type' , '$ip' , '$prevData' , '$newData' ,'$date' )";
                $changes = $dbConnect->query($changes);
                if ($result && $changes) {
                    echo 'success';
                }
            }
            return;
        }
        // III 
        // Check country if it is the same 
        if (strtolower($result[0]['country']) == strtolower($country)) {
            echo "Country has already been registered ";
            return;
        }
        // IV
        // Send Mail
        // $mailSend = detailChangeMail($email , $path , $type , $user , $ip , $date);
        $mailSend = "sent";
        if ($mailSend == "sent") {
            $state = '';
            $address = '';
            $city = '';
            $prevData = createPrevCountryData();
            $newData = createNewCountryData($country, $state, $address, $city);
            // V
            $sql = "UPDATE users SET country = '$country' , city = NULL , address = NULL , state = NULL WHERE user_id = '$id' ";
            $result = $dbConnect->query($sql);

            $changes = "INSERT into changes (user_id, type, ip , prev_data , new_data , timestamp) VALUES ('$id' , '$type' , '$ip' , '$prevData' , '$newData' ,'$date' )";
            $changes = $dbConnect->query($changes);

            if ($result && $changes) {
                echo 'success';
            }
        }
    }
}
// Edit email functionality 
/*
I. Fetch data and sanitize
II. Match password to see if user entered the correct password
III . Compare email to see if it is already registered 
IV . Check to see if new email is in use by someone else
V .Generate a confirmation code and send it as email
VI . Update email_ref and send a verifyEmail as response Text
VII. Set $_SESSION['email']
*/
if (isset($_POST['emailForm'])) {
    // I
    $email = htmlspecialchars($_POST["email"]);
    $prevEmail = $loggedInfo["email"];
    $password = md5(htmlspecialchars($_POST["password"]));
    $id = $loggedInfo["user_id"];
    $currentEmail = $loggedInfo['email'];
    $user = $loggedInfo['firstname'];

    // II
    $sql = "SELECT password  FROM users WHERE user_id = '$id' && password = '$password'";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    if (count($result) == 0) {
        echo "Please enter your current password";
    } else {
        // III 
        if ($currentEmail == $email) {
            echo 'Email has already been registered';
            return;
        }
        // IV
        $sql = "SELECT  email FROM users WHERE email = '$email'";
        $result = $dbConnect->query($sql);
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) == 1) {
            echo "Email is in use";
            return;
        }
        // VII
        $_SESSION["email"] = $email;
        // V
        $code = "";
        $num = "1234567890";
        for ($i = 0; $i < 6; $i++) {
            $code .= $num[rand(0, strlen($num) - 1)];
        }
        $path = "../mail/emailEdit.php";
        // $mailSend =  emailEditMail($email, $code, $path , $user);
        $mailSend = "sent";
        if ($mailSend == "sent") {
            // VI
            $sql = "UPDATE verifications SET email_ref = '$code' WHERE email= '$currentEmail' ";
            $dbConnect->query($sql);
            echo "verifyEmail";
        }
    }
}
