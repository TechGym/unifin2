<?php
include "includes/functions.php";
include "functions/register.func.php";
include "phpMailer.php";
$result = "";
if (isset($logUser)) {
    sleep(1);
    header("Location: ./");
}

function checkValue($name) {
    if (isset($_POST) && isset($_POST[$name])) {
        return $_POST[$name];
    }
    return "";
}

// $_POST["firstname"] = "Asare";
// $_POST["lastname"] = "Foster";
// $_POST["othernames"] = "Solomon Owusu";
// $_POST["email"] = "asare4stertyt@gmail.com";
// $_POST["country"] = "Ghana";
// $_POST['password'] = "aaaaaaaa";
// $_POST['dob'] = "12-22-2003";
// $_POST['state'] = "";
// $_POST['address'] = "";
// $_POST['city'] = "";
/*
1. Sanitize data
2. Handle errors 
3. Fetch referrerer_id 
4. Send verification email
5. Insert  data

*/

if (isset($_POST['firstname'])) {
    $firstName = ucwords(htmlspecialchars($_POST['firstname']));
    $lastName = ucwords(htmlspecialchars($_POST['lastname']));
    $password = htmlspecialchars($_POST['password']);
    $dob = htmlspecialchars($_POST['dob']);
    $othernames = htmlspecialchars($_POST['othernames']);
    $email = strtolower(htmlspecialchars($_POST['email']));
    $country = ucwords(htmlspecialchars($_POST['country']));
    $state = ucwords(htmlspecialchars($_POST['state']));
    $city = ucwords(htmlspecialchars($_POST['city']));
    $address = ucwords(htmlspecialchars($_POST['address']));
    $date = gmdate("Y-m-d H:i:s");

    // Format date 
    $dob = createDOB($dob);

    // Error Handling
    $error = checkEmptyFields($firstName, $lastName, $email, $password, $country, $date, $dob);

    if ($error == false) {

        // Hash password
        $password = md5($password);

        $email_ref = generateKey();


        $referral_by =  findReferral();

        $emailStatus = checkEmail($email);



        if ($emailStatus !== true) {
            $err = $emailStatus;
        } else {
            // $mailSend = sendVerificationEmail($email, $email_ref);
            $mailSend = "sent";
            if ($mailSend == "sent") {
                $verifications = insertIntoVerifications($email, $email_ref);
                $referral = insertReferral($email, $referral_by);
                $user = insertUser($firstName, $lastName, $email, $password, $country, $city, $state, $address, $date, $dob, $othernames);

                if ($user && $referral && $verifications) {
                    // Clear ref cookie and set an email session  to be used for verifications
                    setcookie("ref", "", time() - 3600 * 24 * 30, "/");
                    // Setting and unsetting sessions and
                    $_SESSION["email"] = $_POST['email'];

                    header("Location: ./verifications.php?type=verifyemail");
                } else {
                    echo "error";
                }
            }
        }
    } else {
        $err = $error;
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
    <title>UNIFIN - Register</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/register.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
    <?php if (isset($_GET['auth'])) { ?>
        <script src="./js/recaptcha.js"></script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onLoadFunction&render=explicit" async defer></script>
    <?php } ?>
</head>

<body class="dark">
    <div class="container">
        <section id="logo">
            <img src="./images/logo.png" alt="Logo of Unifi">
        </section>

        <main>
            <?php if (isset($_GET['auth'])) { ?>
                <?php if (isset($_SESSION['recaptchaActive'])) {
                    header("Location: ./register");
                } ?>
                <section class="recaptcha">
                    <h3>Verify recaptcha</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="recaptchaForm" method="POST">
                        <div class="g-recaptcha" id="recaptchaBtn"></div>
                        <div class="error">Dead</div>
                    </form>
                </section>


            <?php } else { ?>
                <?php if (isset($_SESSION['recaptchaActive'])) { ?>
                    <div class="intro">
                        <h1>Sign Up</h1>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="register">
                        <div class="form_element">
                            <label for="firstname">Enter your firstname:</label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <input type="text" name="firstname" id="firstname" placeholder="First name" value="<?php echo checkValue('firstname'); ?>">
                            </div>

                        </div>

                        <div class="form_element">
                            <label for="lastname">Enter your lastname:</label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <input type="text" name="lastname" placeholder="Last name" value="<?php echo checkValue('lastname'); ?>">
                            </div>

                        </div>

                        <div class="form_element">
                            <label for="othernames">Enter your other names: <span>(optional)</span></label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <input type="text" name="othernames" placeholder="Other name(s)" value="<?php echo checkValue('othernames'); ?>">
                            </div>

                        </div>

                        <div class="form_element">
                            <label for="dob">Enter your date of birth: <span>(MM-DD-YYYY)</span></label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <input type="text" name="dob" placeholder="MM-DD-YYYY" id="dob" value="<?php echo checkValue('dob'); ?>">
                            </div>

                        </div>

                        <div class="form_element">
                            <label for="">Enter your email address:</label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <input type="text" name="email" placeholder="Email address" value="<?php echo checkValue('email');  ?>">
                            </div>

                        </div>
                        <div class="form_element">
                            <label for="">Confirm email address:</label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <input type="text" name="cemail" placeholder="Confirm Email address" value="<?php echo checkValue('cemail'); ?>">
                            </div>

                        </div>
                        <div class="form_element country">
                            <label for="">Enter your country name:</label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-globe"></i>
                                </div>
                                <!-- <input name="country" id="country" placeholder="Country" value="<?php echo checkValue('country');  ?>"> -->
                                <select name="country" id="country"></select>
                            </div>

                        </div>

                        <div class="forUs">
                            <div class="form_element">
                                <label for="">Enter state:</label>
                                <div class="content">
                                    <div class="icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <input type="text" name="state" placeholder="State" value="<?php echo checkValue("state") ?>">
                                </div>

                            </div>
                            <div class="form_element">
                                <label for="">Enter your city name:</label>
                                <div class="content">
                                    <div class="icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <input type="text" name="city" placeholder="City" value="<?php echo checkValue("city") ?>">
                                </div>

                            </div>
                            <div class="form_element">
                                <label for="">Enter your address:</label>
                                <div class="content">
                                    <div class="icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <input type="text" name="address" placeholder="Address" value="<?php echo checkValue("address") ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form_element">
                            <label for="">Create a password:</label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input type="password" class="passwords" name="password" id="password" placeholder="***********" value="<?php echo checkValue('password');  ?>">
                                <div class="icon eye">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                            </div>

                        </div>
                        <div class="form_element">
                            <label for="">Confirm your password:</label>
                            <div class="content">
                                <div class="icon">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input class="passwords" type="password" name="confirmPassword" id="confirmPassword" placeholder="***********" value="<?php echo checkValue(("confirmPassword")) ?>">
                                <div class="icon eye">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                            </div>

                        </div>
                        <div class="err allDetailsErr">
                            <div class="container">
                                <p>Please fill in all credentials</p>
                                <button class="confirm">Ok</button>
                            </div>
                        </div>

                        <?php if (isset($err)) { ?>
                            <div class="err logErr">
                                <div class="container">
                                    <p><?php echo $err; ?></p>
                                    <button class="confirm">Ok</button>
                                </div>
                            </div>
                        <?php }  ?>

                        <div class="agree">
                            <input type="checkbox" id="terms" name="terms">
                            <label for="terms">I agree to the <a href="">Terms and Conditions</a> </label>
                        </div>


                        <input type="submit" value="Register" name="register" class="submit" disabled>

                        <p class='login'>Already have an account? <a href="./login">Login</a></p>
                    </form>
                    <script src="./js/register.js" type="module"></script>
                <?php } else {
                    header("Location: ./register?auth=completerecaptcha");
                } ?>
            <?php } ?>
        </main>




    </div>
</body>

</html>