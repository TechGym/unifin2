<?php
include "includes/functions.php";
// NB : The form is validated by JS and sent over to ajax/contact.php
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
    <title>UNIFIN - Contact</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/contact.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
</head>

<body class="dark">
    <?php if (isset($loggedInfo)) {
        include "includes/notifications.php";
    }
    ?>
    <?php include "includes/header.php" ?>
    <?php include "includes/menu.php" ?>
    <div class="container" id="contact">
        <h3>Contact The Press Team</h3>
        <p>Fill out the forms to get in touch with a member of our communications team. Only members of the press will receive a response.
            For all other inquiries please visit <a href="./faq">Unifin's FAQ</a>
        </p>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
            <div class="form_elements">
                <div class="form_element">
                    <label for="fname">First name:</label>
                    <input name="fname" id="fname" aria-label="First name" value="<?php if (isset($_POST['fname'])) {
                                                                                        echo $_POST['fname'];
                                                                                    } ?>">
                </div>

                <div class="form_element">
                    <label for="lname">Last name:</label>
                    <input name="lname" id="lname" aria-label="Last name" value="<?php if (isset($_POST['lname'])) {
                                                                                        echo $_POST['lname'];
                                                                                    } ?>">
                </div>

                <div class="form_element">
                    <label for="email">Business email:</label>
                    <input name="email" id="email" aria-label="Email" value="<?php if (isset($_POST['email'])) {
                                                                                    echo $_POST['email'];
                                                                                } ?>">
                </div>

                <div class="form_element">
                    <label for="organization">Organization:</label>
                    <input name="organization" id="organization" aria-label="Organization" value="<?php if (isset($_POST['organization'])) {
                                                                                                        echo $_POST['organization'];
                                                                                                    } ?>">
                </div>

                <div class="form_element message_element">
                    <label for="message">Message:</label>
                    <textarea name="message" id="message" aria-label="Message"><?php if (isset($_POST['message'])) {
                                                                                    echo $_POST['message'];
                                                                                } ?></textarea>
                </div>
            </div>

            <p class="err"> </p>
            <div class="controls">
                <button class="submit" value="send" name="submit"> Send </button>
                <button name="cancel" class="cancel" value="cancel">Cancel </button>
            </div>

        </form>
    </div>

    <script src="./js/contact.js" type="module" defer></script>

</body>