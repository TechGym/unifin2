<?php

unset($_SESSION['recaptchaActive']);
/*
1. Handles the email verfication on registration 
// For email verification during regstration ($GET['type'] is equal to'verifyEmail')
        I.  Page redirects user to login page if no $_SESSION is set 
        II. If $_GET['key'] is not set
        This page (verifications?type=verifyemail) handles resend email and delete account with the help of ajax/verifications.php
        III. If $_GET['key'] is set, this page verifies the key and updates email status and as well displays a success message
2. Handles email verification during email edits
// For email verification during edits ($GET['type'] is equal to'editEmail')
    I.  Sends email after the email and password has been validated(ajax/edits.php)
    II. If $_session['email'] is not set redirect to profile page
    III.Provides resendCode functionality to be able to resend code 
    IV. With the help of ajax/verifications verifies the input code and updates the email

NB : For email verification during registration the email is verified and updated in this file (1. III)
    though resendEmail and deleteAccount are processed in ajax/verifications
*/

?>
<?php
//  1. I
if ($_GET['type'] == "verifyemail" && !isset($_SESSION['email']) && !isset($_GET['key']) && !isset($_GET['email'])) {
    header("Location: ./login");
}
?>
<?php
// 1. II
if ($_GET['type'] == "verifyemail" && isset($_SESSION['email']) && !isset($_GET['key']) && !isset($_GET['email '])) {
    //Checking Email verification status with email if user has verified email already
    $emailStatus = fetchEmailStatus($_SESSION['email']);
    if ($emailStatus == "verified") {
        header("Location: ./login");
    }

?>
    <?php
    // Display message telling user that an email has been sent to them 
    // Display controls  1. Resend code through email 2. Delete account
    ?>
    <section class="email">
        <h3>
            Verify your Email Address.
        </h3>
        <p>
            A verification link has been sent to :
        </p>
        <p class="contact"><?php echo $_SESSION['email'] ?> </p>
        <p>
            Please click on the link in the message to verify your email address.
        </p>
        <div class="actions">
            <button class="resend">
                Resend Email
            </button>

        </div>
    </section>
    <button class="delete">
        Delete Account
    </button>
<?php } ?>

<?php
// 1. III
if ($_GET['type'] == "verifyemail"  && isset($_GET['key']) && isset($_GET['email'])) {

    // This verifies the link sent to email. 
    $result  = matchEmailToken($_GET["key"], $_GET['email']);
    $time = gmdate("Y-m-d H:i:s");
    if (count($result) == 1 || $result['email_status'] == "verified") {
        // Update email_status when link is valid
        if (count($result) == 1) {
            $email = $result[0]['email'];
            $sql = "UPDATE verifications SET email_status = 'verified' , email_ref = NULL  WHERE email = '$email';";
            $verifications = $dbConnect->query($sql);

            // Setting a unique login key and also its syntax will be used for recovering accounts
            $code = md5($email . $time);

            $sql = "INSERT into cookies (email , cookie , timestamp ) VALUES('$email' , '$code' , '$time')";
            $cookies = $dbConnect->query($sql);
        }
?>
        <div class="successful">
            <?php
            unset($_SESSION['logUser']);
            unset($_SESSION['email']);
            ?>
            <h3>Email verified successfully</h3>
            <p class="redirectLogin" style="margin-bottom:10px;">Wait to be redirected to login into your account</p>
            <p> if you are not redirected <a href="./login" style="color: #00af50; text-decoration:underline"> click here</a> to log in</p>
        </div>

        <script>
            window.addEventListener('load', () => {
                // Redirecting after successful verification of email(registration)
                setTimeout(() => {
                    window.location.href = "./login";
                }, 1000);
            })
        </script>
    <?php  } ?>
<?php } elseif ($_GET['type'] == "verifyemail" && !isset($_SESSION['email']) && !isset($_SESSION['type']) && $_SESSION['key']) { ?>
    <div class="error">
        <h3>
            Invalid verification link
        </h3>
        <p>
            The link you followed is invalid, please click resend on the
            <a href="./verifications.php?type=verifyemail">previous verifications page </a> (if the browser was not closed) or <a href="./login">login to your account</a> to be redirected
        </p>
    </div>
<?php } else { ?>
    <?php if (!isset($_GET['type'])) { ?>
        <div class="error">
            <h3>
                Invite link has expired.
            </h3>
            <p>
                To regenerate an invite link, please click resend on the
                <a href="./verifications.php?type=verifyemail">previous verifications page </a> (if the browser was not closed) or <a href="./login">login to your account</a> to be redirected
            </p>
        </div>
    <?php } ?>
<?php } ?>


<?php
// 2
if ($_GET['type'] == "editEmail") {
    if (isset($_SESSION['email'])) { ?>
        <section class="verifyDetails">
            <h3>
                Verify your Email Address
            </h3>
            <p>
                A verification code has been sent to :
            </p>
            <p class="contact"><?php echo $_SESSION['email'] ?> </p>
            <p>
                Enter the code below to verify your email address
            </p>
            <form action="" id="verify" class="editEmail">
                <input type="text" placeholder="Enter code here">
                <input type="submit" value="Submit">
            </form>
            <p class="msgError"></p>

        </section>
        <button class="displayResend">Didn't receive code?</button>
        <div class="verify_action" id="verify_action">
            <button class="resend">
                Resend Email
            </button>
        </div>
    <?php } else {
        header("Location: ./profile");
    } ?>
<?php } ?>