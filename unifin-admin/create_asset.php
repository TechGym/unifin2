<?php
require_once "../includes/functions.php";
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
  <title>UNIFIN - Create Asset</title>
  <link rel="shortcut icon" href="../images/logo.png" type="image/png">
  <link rel="stylesheet" href="../css/includes.css">
  <link rel="stylesheet" href="../css/create_assets.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>


<body class="dark">
  <div class="container">
    <section id="logo">
      <img src="../images/logo.png" alt="Logo of Unifi">
    </section>
    <main>
      <div class="intro">
        <h1>Create asset</h1>
      </div>
      <div id="success">
        Asset successfully created, exiting...
      </div>
      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="create_asset">
        <div class="form_element">
          <input type="text" name="assetname" placeholder="Asset's name(code)" value="<?php
                                                                                      if (isset($_POST['assetname'])) {
                                                                                        echo $_POST['assetname'];
                                                                                      } ?>">
        </div>
        <div class="form_element">
          <input type="text" name="limit" placeholder="Enter asset's start limit" value="<?php
                                                                                          if (isset($_POST['limit'])) {
                                                                                            echo $_POST['limit'];
                                                                                          } ?>">
        </div>

        <div class="form_element">
          <input type="text" name="domain" placeholder="Enter asset's domain here " value="<?php
                                                                                            if (isset($_POST['domain'])) {
                                                                                              echo $_POST['domain'];
                                                                                            } ?>">
        </div>
        <div class="form_element">
          <input type="password" class="seedphrases" name="seedphrase1" id="seedphrase1" placeholder="Issuer's seed phrase" value="<?php
                                                                                                                                    if (isset($_POST['seedphrase1'])) {
                                                                                                                                      echo $_POST['seedphrase1'];
                                                                                                                                    } ?>">

        </div>
        <div class="form_element">

          <input class="seedphrases" type="password" name="seedphrase2" id="seedphrase2" placeholder="Distributor's seed phrase" value="<?php
                                                                                                                                        if (isset($_POST['seedphrase2'])) {
                                                                                                                                          echo $_POST['seedphrase2'];
                                                                                                                                        } ?>">

        </div>

        <p class="err"> </p>
        <div class="controls">
          <input type="submit" value="Create asset and proceed" name="proceed" class="proceed" title="Create the asset and proceeding to creating the assets information file ">
          <button type="submit" value="Create asset and exit" name="exit" class="exit" title="Create the asset and exit. Will come back to create the assets information file">Create asset and exit </button>
        </div>



      </form>

      <p class="readMore">You new to information needed to create an asset? <a href="https://developers.stellar.org/docs/issuing-assets/publishing-asset-info" target="_blank">Read more here</a></p>
    </main>


  </div>

  <script src="./js/create_asset.js">
  </script>
</body>

</html>