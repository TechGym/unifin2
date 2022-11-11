<?php
require_once "../includes/functions.php";
statusRedirect();
if (!checkCouncilOf5($loggedInfo['email'])) {
  header("Location: ../");
};

$type = $_GET['type'];
// Check if type exists
$types = ["council5", "council50", "individual", "complete_team", "all"];
if (!isset($type) || !in_array(strtolower($type), $types)) {
  header("Location: ../admin");
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
  <title>UNIFIN - Send Tip</title>
  <link rel="shortcut icon" href="../images/logo.png" type="image/png">
  <link rel="stylesheet" href="../css/includes.css">
  <link rel="stylesheet" href="../css/send_tip.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">

  <div class="confirmation">
    <?php if ($type === "council5") {
      $text = "the council of 5 members";
    } ?>
    <?php if ($type === "council50") {
      $text = "the council of 50 members";
    } ?>
    <?php if ($type === "all") {
      $text = "all members";
    } ?>
    <?php if ($type === "complete_team") {
      $text = "all members with a complete team";
    } ?>

    <h3>Please enter the number of tokens you want to send to <?php echo $text ?></h3>

    <form action="" id="sendTipForm" class="<?php echo $type ?>">
      <input type=" text" placeholder="0.00" name="amount">
      <p class="err">Please enter a valid tip amount (numbers only , to 2dp)</p>
      <div class="form_controls">
        <input type="submit" value="Confirm Payment" name="sendTip">

        <button>Cancel Payment</button>
      </div>

    </form>
    <script src=" ../js/jquery.js" defer></script>
    <script src="./js/send_tip.js" type="module"></script>
  </div>

</body>

</html>