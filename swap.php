<?php
require_once "includes/functions.php";
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
  <title>UNIFIN - Swap</title>
  <link rel="shortcut icon" href="./images/logo.png" type="image/png">
  <link rel="stylesheet" href="./css/includes.css">
  <link rel="stylesheet" href="./css/swap.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
  <style></style>
</head>

<body class="dark">

  <main id="swap">
    <div class="includes">
      <?php include "includes/header.php" ?>
      <?php include "includes/menu.php" ?>
    </div>
    <div id="container">
      <iframe style="max-width:370px;" class="widget__frame" src="https://godex.io/widget?coin_from=ETH&coin_to=XLM&amount=1" scrolling="no"> Can't load widget </iframe>
    </div>
  </main>
  <script src="./js/default.js" type="module" defer> </script>

</body>

</html>