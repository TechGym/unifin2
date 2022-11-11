<?php
require_once 'includes/functions.php';
if (!isset($loggedInfo)) {
  header("Location: ./");
}
if (isset($_POST['submit']) && !isset($_POST['method'])) {
  $err = "Please select an option";
}
if (isset($_POST['method'])) {
  $method = $_POST['method'];
  if ($method == "cash") {
    header("Location: ./payment?type=cash");
  }
  if ($method == "other") {
    header("Location: ./payment?type=other");
  }
  if ($method == "xlm") {
    header("Location: ./payment?type=native");
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
  <title>UNIFIN - Payment</title>
  <link rel="shortcut icon" href="./images/logo.png" type="image/png">
  <link rel="stylesheet" href="./css/includes.css">
  <link rel="stylesheet" href="./css/payment.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
  <style></style>
</head>

<body class="dark">
  <?php include "includes/header.php" ?>
  <?php include "includes/menu.php" ?>
  <?php include "includes/notifications.php" ?>
  <main id="payment">
    <?php if (!isset($_GET['type'])) { ?>
      <section id="select_payment">
        <h3>Choose a payment method</h3>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST" id="choose_method">
          <div class="option">
            <input type="radio" name="method" id="xlm" value="xlm">
            <label for="XML">XLM</label>
          </div>

          <div class="option">
            <input type="radio" name="method" id="other" value="other">
            <label for="other">Other cryptocurrencies</label>
          </div>

          <div class="option">
            <input type="radio" name="method" id="cash" value="cash">
            <label for="cash">Cash payment</label>
          </div>
          <?php if (isset($err)) { ?>
            <p class="err"> <?php echo $err; ?></p>
          <?php } ?>
          <input type="submit" value="Proceed to payment" name="submit" class="submit">

        </form>
      </section>
    <?php } ?>
    <?php if ($_GET['type'] === "native") { ?>
      <section id="instructions">
        <h3>instructions</h3>
        <div class="instruction">
          <div class="index">1.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>
        <div class="instruction">
          <div class="index">2.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natu temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>
        <div class="instruction">
          <div class="index">3.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>

        <button class="proceed_to_payment">
          Proceed To Payment
        </button>
      </section>

      <section id="payment">
        <form action="">
          <div class="form_controls">
            <div class="actualFormHere"></div>
            <input type="submit" value="submit" name="submit">
            <button class="cancel">Return to instructions</button>
          </div>
        </form>
      </section>

    <?php } ?>
    <?php if ($_GET['type'] === "other") { ?>
      <section id="instructions">
        <h3>instructions</h3>
        <div class="instruction">
          <div class="index">1.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>
        <div class="instruction">
          <div class="index">2.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natu temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>
        <div class="instruction">
          <div class="index">3.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>

        <button class="proceed_to_payment">
          Proceed To Payment
        </button>
      </section>

      <section id="payment">
        <form action="">
          <div class="form_controls">
            <div class="actualFormHere"></div>
            <input type="submit" value="submit" name="submit">
            <button class="cancel">Return to instructions</button>
          </div>
        </form>
      </section>

    <?php } ?>
    <?php if ($_GET['type'] === "cash") { ?>
      <section id="instructions">
        <h3>instructions</h3>
        <div class="instruction">
          <div class="index">1.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>
        <div class="instruction">
          <div class="index">2.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natu temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>
        <div class="instruction">
          <div class="index">3.</div>
          <div class="value">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur molestias consequatur temporibus voluptates quas? Similique minus deleniti alias incidunt natus?</div>
        </div>

        <button class="proceed_to_payment">
          Proceed To Payment
        </button>
      </section>
      <section id="payment">
        <form action="">
          <div class="form_controls">
            <div class="actualFormHere"></div>
            <input type="submit" value="submit" name="submit">
            <button class="cancel">Return to instructions</button>
          </div>
        </form>
      </section>
    <?php } ?>
  </main>
  <script src="./js/default.js" type="module" defer></script>
  <script src="./js/payment.js" type="module" defer></script>


</body>

</html>