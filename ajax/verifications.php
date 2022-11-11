<?php
include "../includes/functions.php";
include "../phpMailer.php";
// <!--  Verify email on registration -->

/*
// Verification page is in 4 parts
1. Setup email address verifcation code on registration
2 .Verify edit and add phone
3. Verify edit email
NB : 2 andd 3  are initiated by edits.php
*/
if (isset($_SESSION["email"])) {
    $email = $_SESSION['email'];
}

/*
// Verify email on registration steps
1. Resend Verification code 
2. Delete account
NB: This page serves as the page where user can request for a new verification email
 or delete the account during the registration process. The functionality of updating  
 email status is in includes/verifyemail.php
*/

/*
1.  Create a random verification reference
2 . Check for the status of the email first if verified redirect to login page
3.  Send new email
4. Update verifications table
*/
if (isset($_POST["resendRegistrationCode"])) {

    $email_ref = generateKey();

    $emailStatus = fetchEmailStatus($email);
    if ($emailStatus == "verified") {
        header("Location: ./login");
        return;
    }
    $path = "../mail/registerVerification.php";
    // $mailSend =  emailVerificationMail($email, $email_ref, $path);
    $mailSend = 'sent';
    if ($mailSend == "sent") {
        $verifications = "UPDATE  verifications SET  email_ref = '$email_ref' WHERE email = '$email'";
        $dbConnect->query($verifications);
    } else {
        echo error_get_last()['message'];
    }
}

/* Delete the information of the person from the users,membership and verifications table */
if (isset($_POST['deleteAccount'])) {
    $users = "DELETE from users WHERE email = '$email'";
    $verifications = "DELETE from verifications WHERE email = '$email'";
    $membership = "DELETE from membership WHERE user_email = '$email'";
    if (
        $dbConnect->query($membership) &&
        $dbConnect->query($users) &&
        $dbConnect->query($verifications)
    ) {
        unset($_SESSION['email']);
        echo "deletedAccount";
    }
}

// <!--  Update email and phone during edits -->
// RESEND EDIT VERIFICATION CODE (EMAIL AND PHONE)
/*
1. Targets the type of edit 
2. Generates random code and updates database based on type of edit
3 . send an sms or email 
*/
if (isset($_POST["resendDetailChangeCode"])) {
    $type = $_POST['type'];
    if ($type == "editPhone") {
        $email = $loggedInfo["email"];
        if (isset($_SESSION['phone'])) {
            $phone = $_SESSION['phone'];
        } elseif (isset($loggedInfo['phone'])) {
            $phone = $loggedInfo['phone'];
        }
        // Code will be generated , updated and sent in functions.php(sendSMS)
        echo sendSMS($email, $phone);
    }
    if ($type == "editEmail") {
        $key = "";
        $num = "1234567890";
        $email = $_SESSION["email"];
        $oldEmail = $loggedInfo["email"];

        $user =  $loggedInfo["firstname"];
        for ($i = 0; $i < 6; $i++) {
            $key .= $num[rand(0, strlen($num) - 1)];
        }
        // Send email with new code.
        $path = "../mail/emailEdit.php";
        // $mailSend =  emailEditMail($email, $key, $path , $user);
        $mailSend = "sent";
        if ($mailSend == "sent") {
            $verifications = "UPDATE  verifications SET  email_ref = '$key' WHERE email = '$oldEmail'";
            if ($dbConnect->query($verifications)) {
                echo "mailCodeResent";
            };
        }
    }
}

// VERIFY EDIT VERIFICATION CODE (EMAIL AND PHONE)
/*
1. Targets the type of edit 
2. Selects the ref based on the type of edit
3 . If ref matches the passed in code at the client side , 
    typeStatus is verified and updatedDetails sent as response to verification.js
    $_SESSION['type'] is also unset
4 . If code doesn't match an error is sent 
*/
if (isset($_POST['editType'])) {
    $editType = $_POST["editType"];
    if ($editType == "editPhone") {
        $phone_ref = htmlspecialchars($_POST['code']);
        $phone = $_SESSION['phone'];
        $email = $loggedInfo["email"];
        // Matching code
        $sql = "SELECT email FROM verifications WHERE email = '$email' && phone_ref = '$phone_ref'";
        $result = $dbConnect->query($sql);
        if ($result->num_rows == 1) {
            // $mailSend = detailChangeMail($email, "../mail/detailChange.php", "phone number");
            $mailSend = "sent";
            if ($mailSend == "sent") {
                //  Update phone
                $sql = "UPDATE users SET phone = '$phone' WHERE email = '$email' ";
                $users = $dbConnect->query($sql);
                $sql = "UPDATE verifications SET phone_status = 'verified', phone_ref = NULL WHERE email = '$email'";
                $result = $dbConnect->query($sql);
                if ($result && $users) {
                    unset($_SESSION["phone"]);
                    echo "updatedDetails";
                }
            }
        } else {
            echo "editDetailError";
        }
        return;
    }
    if ($editType = "editEmail") {
        $email_ref = htmlspecialchars($_POST['code']);
        $newEmail = $_SESSION['email'];
        $id = $loggedInfo["user_id"];

        $ip = fetchIp();
        $date = gmdate("Y-m-d H:i:s");
        $user = $loggedInfo['firstname'];
        $path =  "../mail/detailChange.php";
        $currentEmail = $loggedInfo["email"];
        $type = "personal informaton (email address)";
        $cookie = generateKey();


        $prevData = $loggedInfo['email'];
        $newData = $newEmail;
        // Matching code 
        $sql = "SELECT email FROM verifications WHERE email = '$currentEmail' && email_ref = '$email_ref'";
        $result = $dbConnect->query($sql);
        if ($result->num_rows == 1) {
            // check new email
            $sql = "SELECT email FROM verifications WHERE email = '$newEmail'";
            $result = $dbConnect->query($sql);
            if ($result->num_rows == 1) {
                echo "Email is already in use";
                return;
            }
            // Sending the mail 
            // $mailSend = detailChangeMail($currentEmail, $path , $type , $user , $ip , $date);
            $mailSend = "sent";
            if ($mailSend == "sent") {
                // // Update email on users table
                $sql = "UPDATE users SET email = '$newEmail' WHERE user_id = '$id' ";
                $users = $dbConnect->query($sql);
                // // Update email on tokens table
                $sql = "UPDATE tokens SET user_email = '$newEmail' WHERE user_id = '$id' ";
                $tokens = $dbConnect->query($sql);
                // Update email on verifications table
                $sql = "UPDATE verifications SET email = '$newEmail' , email_status = 'verified',  email_ref = NULL WHERE email = '$currentEmail' ";
                $verifications = $dbConnect->query($sql);
                // // Update email on cookies table
                $sql = "UPDATE cookies SET email = '$newEmail' , timestamp ='$date' , cookie = '$cookie' WHERE email = '$currentEmail' ";
                $tokens = $dbConnect->query($sql);
                // Update email on membership table
                $sql = "UPDATE membership SET user_email = '$newEmail' WHERE user_email = '$currentEmail' ";
                $verifications = $dbConnect->query($sql);

                unset($_SESSION["email"]);
                setcookie(md5('logUser'), $cookie, time() + 3600 * 24 * 3, "/");

                $changes = "INSERT into changes (user_id, type, ip , prev_data , new_data , timestamp) VALUES ('$id' , '$type' , '$ip' , '$prevData' , '$newData' ,'$date' )";
                $changes = $dbConnect->query($changes);
                if ($changes) {
                    echo 'updatedDetails';
                }
            }
        } else {
            echo "editDetailError";
        }
    }
}
