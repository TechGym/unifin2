<?php
include "includes/functions.php";
include "functions/verifications.func.php";
include "phpMailer.php";
// If the user has already loggedIn redirect to homepage
if (isset($logUser)) {
    sleep(1);
    header("Location: ./");
}

// Verify and submit to database
if (filter_has_var(INPUT_POST, "login")) {
    $logUser = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password = md5($password);

    $passwordMatch = matchPassword($logUser, $password);
    if ($passwordMatch == 1) {
        // Fetching the email status
        $email = $_POST['email'];
        $status = fetchEmailStatus($email);
        if ($status == "unverified") {
            // If user's email is unverified
            $_SESSION['email'] = $logUser;
            // Sending the mail 
            $email_ref = generateKey();
            $cookie = md5($email . gmdate("Y-m-d"));
            $to = $email;
            $path = "mail/registerVerification.php";
            // $mailSend = emailVerificationMail($to, $email_ref, $path);
            $mailSend = "sent";
            if ($mailSend == "sent") {
                // Updating email_ref
                $sql = "UPDATE verifications SET email_ref = '$email_ref'  WHERE email = '$logUser';";
                $result = $dbConnect->query($sql);


                // Redirecting
                header("Location: ./verifications?type=verifyemail");
            }
        } else {

            // Setting new cookie on login 
            $timestamp = gmdate("Y-m-d H:i:s");
            $cookie = md5($email . $timestamp);
            $sql = "UPDATE cookies SET cookie = '$cookie' , log_count = log_count + 1 ,  timestamp = '$timestamp'  WHERE email = '$logUser'";
            $dbConnect->query($sql);

            if ($_POST['cookie'] === "on") {
                // Keep me logged in sets cookie to 1 month
                setcookie(md5('logUser'), "$cookie", time() + 3600 * 24 * 3, "/");
            } else {
                $_SESSION[md5('logUser')] = $cookie;
            }
            header("Location: ./dashboard");
        }
    } else {
        $logErr = "Invalid login credentials !!!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache , no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>UNIFIN - Log In</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/login.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
</head>

<body class="dark">
    <div class="container">
        <section id="logo">
            <img src="./images/logo.png" alt="Logo of Unifi">
        </section>
        <main>
            <div class="intro">
                <h1>Log In</h1>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <?php if (isset($logErr)) { ?>
                    <p class="error"><?php echo $logErr ?></p>
                <?php } ?>
                <div class="form_element">
                    <div class="icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input type="text" name="email" placeholder="Email address" value="<?php
                                                                                        if (isset($_POST['email'])) {
                                                                                            echo $_POST['email'];
                                                                                        }
                                                                                        ?>">
                </div>

                <div class="form_element">
                    <div class="icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" aria-label="Password" name="password" id="password" placeholder="***********" value="<?php
                                                                                                                                if (isset($_POST['password'])) {
                                                                                                                                    echo $_POST['password'];
                                                                                                                                } ?>">
                    </input>
                    <div class="icon eye">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                </div>
                <div class="forgotPassword">
                    <a href="./reset"> Forgot password</a>
                </div>
                <input type="checkbox" id="cookie" name="cookie" checked>
                <label for="cookie">Keep me logged in</label>
                <input type="submit" value="Log In" name="login" class="submit">
                <p class='register'>Don't have an account? <a href="./register.php">Register</a></p>
            </form>
        </main>

    </div>
</body>
<script src="./js/login.js" type="module" defer></script>

</html>