<?php
include "includes/functions.php";
include "./phpMailer.php";

/*
1. Sanitize inputs
2. Match password 
3. Validate new password 
4. Update password and redirect
NB: Password change is not submitted by ajax but by the form directly
*/
if (filter_has_var(INPUT_POST, "submitPassword")) {
    $password = md5(htmlspecialchars($_POST['currentPassword']));
    $newPassword = md5(trim(htmlspecialchars($_POST['newPassword'])));
    $confirmPassword = md5(trim(htmlspecialchars($_POST['confirmPassword'])));
    $id = $loggedInfo['user_id'];

    // Fetching password
    $passwordMatch = matchPassword($loggedInfo['email'], $password);

    if ($passwordMatch) {
        if (strlen($newPassword) < 8) {
            $newPasswordErr = "Password is too short; spaces are trimmed ";
        } else {
            if ($newPassword != $confirmPassword) {
                $confirmPasswordErr = "Passwords do not match";
            } else {
                if ($newPassword == $password) {
                    $confirmPasswordErr = "Password is already in use ";
                } else {
                    $email = $loggedInfo['email'];
                    $ip = fetchIp();
                    $user = $loggedInfo['firstname'];
                    $prevData = $password;
                    $path = "../mail/detailChange.php";
                    $date = gmdate("Y-m-d H:i:s");

                    // Sending mail and inserting password change
                    // $mailSend = detailChangeMail($email, $path, "password", $user, $ip, $date);

                    $changes = "INSERT into changes (user_id, type, ip , prev_data , new_data , timestamp) VALUES ('$id' , 'password change' , '$ip' , '$prevData' , '$newPassword' ,'$date' )";
                    $changes = $dbConnect->query($changes);

                    $mailSend = 'sent';
                    if ($mailSend == 'sent' && $changes) {
                        // Update password and redirect 
                        $sql = "UPDATE users SET password = '$newPassword' WHERE user_id ='$id'";
                        if ($dbConnect->query($sql)) {
                            header("Location: ./profile");
                        };
                    }
                }
            }
        }
    } else {
        $currentPasswordErr = "Invalid current password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache , no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>UNIFIN - Profile</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/passwordChange.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
    <main>
        <section id="passwordChange">
            <h3>Change Password</h3>
            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                <div class="form_element">
                    <label for="currentPassword">Enter current Password</label>
                    <input type="password" class="passwords" name="currentPassword" id="currentPassword" placeholder="***********" value="<?php
                                                                                                                                            if (isset($_POST['currentPassword'])) {
                                                                                                                                                echo $_POST['currentPassword'];
                                                                                                                                            } ?>">

                </div>
                <?php if (isset($currentPasswordErr)) { ?>
                    <p class="err"><?php echo $currentPasswordErr ?> </p>
                <?php } ?>
                <div class="form_element">
                    <label for="newPassword">Enter new Password</label>
                    <input type="password" class="passwords" name="newPassword" id="newPassword" placeholder="***********" value="<?php
                                                                                                                                    if (isset($_POST['newPassword'])) {
                                                                                                                                        echo $_POST['newPassword'];
                                                                                                                                    } ?>">

                </div>
                <?php if (isset($newPasswordErr)) { ?>
                    <p class="err"><?php echo $newPasswordErr ?> </p>
                <?php } ?>
                <div class="form_element">
                    <label for="confirmPassword">Confirm new Password</label>
                    <input type="password" class="passwords" name="confirmPassword" id="confirmPassword" placeholder="***********" value="<?php
                                                                                                                                            if (isset($_POST['confirmPassword'])) {
                                                                                                                                                echo $_POST['confirmPassword'];
                                                                                                                                            } ?>">

                </div>
                <?php if (isset($confirmPasswordErr)) { ?>
                    <p class="err"><?php echo $confirmPasswordErr ?> </p>
                <?php } ?>
                <div class="form_controls">
                    <input type="submit" value="Submit" name="submitPassword">
                    <button class='cancel'>Cancel</button>
                </div>
            </form>
        </section>
    </main>
    <script src="./js/passwordChange.js"></script>
</body>