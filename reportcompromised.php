<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache , no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>UNIFIN - Report</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/compromised.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
</head>

<body class="dark">
    <div class="container" id="report">
        <h3>Report Comromised Account</h3>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
            <textarea name="heading" id="heading" aria-label="Heading" placeholder="Enter heading of report here"><?php if (isset($_POST['heading'])) {
                                                                                                                        echo $_POST['heading'];
                                                                                                                    } ?></textarea>

            <textarea name="message" id="message" aria-label="Message" placeholder="Enter your report here"><?php if (isset($_POST['message'])) {
                                                                                                                echo $_POST['message'];
                                                                                                            } ?></textarea>

            <p class="err"> </p>
            <div class="controls">
                <button class="submit" value="send" name="submit"> Submit </button>
                <button name="cancel" class="cancel" value="cancel">Cancel </button>
            </div>

        </form>
    </div>

    <script src="./js/contact.js" type="module" defer></script>
</body>