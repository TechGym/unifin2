<?php
include "includes/functions.php";
if (isset($loggedInfo)) {
  header("Location: ./");
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
  <title>UNIFIN - Forgot Password</title>
  <link rel="shortcut icon" href="./images/logo.png" type="image/png">
  <link rel="stylesheet" href="./css/includes.css">
  <link rel="stylesheet" href="./css/reset.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
</head>

<body class="dark">
  <main class="container">
    <?php if (!isset($_GET['rel'])) { ?>
      <section class="email">
        <h3>
          Find your account
        </h3>
        <form action="" method="post" id="findUser">

          <label for="email">Enter your email address to find your account</label>
          <input type="text" name="email" id="email">

          <p class="err">Please fill in all credentials</p>
          <div class="form_controls">
            <button class="submit"> Find Account </button>
            <button class="cancel">Cancel</button>
          </div>
        </form>

      </section>
    <?php } elseif (isset($_GET['rel']) && $_GET["rel"] == 'key') {
      if (!isset($_SESSION['email'])) {
        header("Location:./reset");
      } else if (isset($_SESSION['confirmed'])) {
        header("Location:./reset?rel=set");
      } ?>
      <section class="email">
        <h3>
          Enter code below to confirm
        </h3>
        <p>A reset code has been sent to your email. Enter code below</p>
        <form action="" method="post" id="confirmEmail">

          <label for="code">Enter code here:</label>
          <input type="text" name="code" id="code">

          <p class="err">Please fill in all credentials</p>

          <div class="form_controls">
            <button class="submit"> Confirm Code</button>
            <button class="cancel">Cancel</button>
          </div>
        </form>

      </section>

    <?php } elseif (isset($_GET['rel']) && $_GET["rel"] == 'set') { ?>
      <section class="email">
        <h3>
          Set a new password
        </h3>
        <form action="" method="post" id="passwordReset">

          <label for="password">Enter a new password:</label>
          <input type="password" name="password" id="password">

          <label for="Cpassword">Confirm password:</label>
          <input type="password" name="Cpassword" id="Cpassword">

          <p class="err">Please fill in all credentials</p>

          <div class="form_controls">
            <button class="submit"> Confirm Code</button>
            <button class="cancel">Cancel</button>
          </div>
        </form>

      </section>

    <?php } ?>

  </main>

  <script src="./js/reset.js" type="module" defer></script>
</body>