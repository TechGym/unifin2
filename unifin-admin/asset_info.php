<?php
require_once "../includes/functions.php";
if (isset($_POST['category'])) {
  $category = $_POST['category'];
  header("Location: ./asset_info?category=$category");
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
  <title>UNIFIN - Create Asset Information</title>
  <link rel="shortcut icon" href="../images/logo.png" type="image/png">
  <link rel="stylesheet" href="../css/includes.css">
  <link rel="stylesheet" href="../css/asset_info.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">

  <?php if (!isset($_GET['category']) && !isset($_GET['type'])) {
    header("Location: ./asset_info?category=new");
  } ?>

  <?php if (isset($_GET['category']) && !isset($_GET['type'])) {
    if ($_GET['category'] !== "new") {
      header("Location: ./asset_info?category=new");
    }
  ?>
    <main id="steps">
      <section class="step1 step">

        <div class="heading">
          Accounts Information
        </div>
        <div class="map">
          <div class="circle">1</div>
          <div class="line"></div>
          <div class="circle">2</div>
          <div class="line"></div>
          <div class="circle">3</div>
          <div class="line"></div>
          <div class="circle">4</div>
        </div>

        <div class="content">
          <form action="<?php echo $_SERVER['PHP_SELF'] ?> " id="accounts" method="POST">
            <label for="accounts">
              Enter all public keys related to this account <span>(comma separated, do not press enter)</span> <span class="required">*</span>
            </label>
            <textarea name="public_keys" id="public_keys" cols="30" rows="10" placeholder="GCUIY4R2ZNQB7AUWDG3P56FYQJNPTEJPVXVPXWESWW3RNAG6XUE5FZXO,GDU6AUJKAJLD76NMIAX5YH56BECPE6FDA47CFQNZWOQCWVJXYDG6J7ZI, GDZIX3ILRUH4M242FNHRLKDVR72PGFKYLIXWQXYICF2MZOYPUVQL7WK4"></textarea>

            <div class="token_type">
              <p>Choose token type: <span class="required">*</span></p>
              <div class="option">
                <input type="radio" id="native" name="option" value="native" checked>
                <label for="native">Native</label>
              </div>

              <div class="option">
                <input type="radio" id="anchored" name="option" value="anchored">
                <label for="anchored">Anchored</label>
              </div>
            </div>

            <p class="err">Dead</p>
            <input type="submit" value="Proceed">

          </form>

        </div>

        <p class="information note">
          You are advised to edit the file manually if you want to add additional information to it.
          <a href="https://developers.stellar.org/docs/issuing-assets/publishing-asset-info" target="_blank">Click here to learn more about information that can be added </a>
        </p>

      </section>
      <section class="step2 step">

        <div class="heading">
          Organization's Documentation
        </div>
        <div class="map">
          <div class="circle">1</div>
          <div class="line"></div>
          <div class="circle">2</div>
          <div class="line"></div>
          <div class="circle">3</div>
          <div class="line"></div>
          <div class="circle">4</div>
        </div>

        <div class="content">
          <form action="<?php echo $_SERVER['PHP_SELF'] ?> " id="accounts" method="POST">
            <div class="form_element">
              <label for="org_name">Organization's Name: <span class="required">*</span></label>
              <input type="text" name="org_name" id="org_name" placeholder="Organization's Name" value="<?php echo preserveFormValue("org_name"); ?>">
            </div>

            <div class="form_element">
              <label for="org_dba">Organization's DBA: </label>
              <input type="text" name="org_dba" id="org_dba" placeholder="Organization's DBA" value="<?php echo preserveFormValue("org_dba"); ?>">
            </div>

            <div class="form_element">
              <label for="org_url">Organization's URL: <span class="required">*</span></label>
              <input type="text" name="org_url" id="org_url" placeholder="http://www.organization_website.com" value="<?php echo preserveFormValue("org_url"); ?>">
            </div>

            <div class=" form_element">
              <label for="org_physical_address">Organization's Physical Address:</label>
              <textarea type="text" name="org_physical_address" id="org_physical_address" placeholder="At least state and country"><?php echo preserveFormValue("org_physical_address"); ?></textarea>
            </div>

            <div class="form_element">
              <label for="org_logo_link">Organization's LOGO (link):<span class="required">*</span></label>
              <input type="text" name="org_logo" id="org_logo_link" placeholder="https://domain/../picture.ext" value="<?php echo preserveFormValue("org_logo_link"); ?>">
            </div>

            <div class="form_element">
              <label for="org_official_email">Organization's Official Email:<span class="required">*</span></label>
              <input type="text" name="org_official_email" id="org_official_email" placeholder="hello@org.com" value="<?php echo preserveFormValue("org_official_email"); ?>">
            </div>

            <div class="form_element">
              <label for="org_support_email">Organization's Support Email:</label>
              <input type="text" name="org_support_email" id="org_support_email" placeholder="support@org.com" value="<?php echo preserveFormValue("org_support_email"); ?>">
            </div>

            <div class="form_element">
              <label for="org_twitter">Organization's Twitter Account:</label>
              <input type="text" name="org_twitter" id="org_twitter" placeholder="http://twitter.com/organization" value="<?php echo preserveFormValue("org_twitter"); ?>">
            </div>

            <div class="form_element">
              <label for="org_github">Organization's Github Account:</label>
              <input type="text" name="org_github" id="org_github" placeholder="http://github.com/organization" value="<?php echo preserveFormValue("org_github"); ?>">
            </div>

            <div class="form_element">
              <label for="org_description">Organization's Description:<span class="required">*</span></label>
              <textarea type="text" name="org_description" id="org_description" placeholder="Organization description here"><?php echo preserveFormValue("org_description"); ?></textarea>
            </div>

            <p class="err">Dead</p>
            <div class="form_controls">
              <button value="Prev"> Prev</button>
              <input type="submit" value="Next">
            </div>


          </form>

        </div>



      </section>
      <section class="step3 step">

        <div class="heading">
          Contact Documentation
          <p class="desc">This should be the contact details of one of your staff</p>
        </div>
        <div class="map">
          <div class="circle">1</div>
          <div class="line"></div>
          <div class="circle">2</div>
          <div class="line"></div>
          <div class="circle">3</div>
          <div class="line"></div>
          <div class="circle">4</div>
        </div>

        <div class="content">
          <form action="<?php echo $_SERVER['PHP_SELF'] ?> " id="accounts" method="POST">
            <div class="form_element">
              <label for="name">Staff Member's Name: <span class="required">*</span></label>
              <input type="text" name="name" id="name" placeholder="John Doe" value="<?php echo preserveFormValue("name"); ?>">
            </div>

            <div class=" form_element">
              <label for="email">Staff Member's Email Address:<span class="required">*</span></label>
              <input type="text" name="email" id="email" placeholder="user@example.com" value="<?php echo preserveFormValue("email"); ?>">
            </div>

            <div class=" form_element">
              <label for="github">Staff Member's Github Account:</label>
              <input type="text" name="github" id="github" placeholder="http://github.com/user" value="<?php echo preserveFormValue("github"); ?>">
            </div>

            <div class=" form_element">
              <label for="keybase">Staff Member's Keybase Account:</label>
              <input type="text" name="keybase" id="keybase" placeholder="https://keybase.io/user" value="<?php echo preserveFormValue("keybase"); ?>">
            </div>



            <div class=" form_element">
              <label for="twitter">Staff Member's Twitter Account:</label>
              <input type="text" name="twitter" id="twitter" placeholder="https://twitter.com/user" value="<?php echo preserveFormValue("twitter"); ?>">
            </div>

            <p class=" err">Dead</p>
            <div class="form_controls">
              <button value="Prev"> Prev</button>
              <input type="submit" value="Next">
            </div>


          </form>

        </div>



      </section>
      <section class="step4 step">

        <div class="heading">
          Currency Documentation

        </div>
        <div class="map">
          <div class="circle">1</div>
          <div class="line"></div>
          <div class="circle">2</div>
          <div class="line"></div>
          <div class="circle">3</div>
          <div class="line"></div>
          <div class="circle">4</div>
        </div>

        <div class="content">
          <form action="<?php echo $_SERVER['PHP_SELF'] ?> " id="accounts" method="POST">
            <div class="form_element">
              <label for="code">Assets's code: <span class="required">*</span></label>
              <input type="text" name="code" id="code" placeholder="Enter asset's code here">
            </div>

            <div class="form_element">
              <label for="issuer">Issuer's public address: <span class="required">*</span></label>
              <input type="text" name="issuer" id="issuer" placeholder="Enter issuer's public key here">
            </div>

            <div class="form_element">
              <label for="conditions">Conditions of asset: </label>
              <textarea type="text" name="conditions" id="conditions" cols="5" rows="30" placeholder="Conditions needed to be met by a user before they can hold your token"></textarea>
            </div>

            <section class="anchored_asset_form">
              <p class="description">
                Required for anchored assets
              </p>
              <div class="form_element">
                <label for="anchor_asset_type">Anchor Asset Type:<span class="required">*</span></label>
                <input type="text" name="anchor_asset_type" id="anchor_asset_type" placeholder="Anchor Asset Type">
              </div>

              <div class="form_element">
                <label for="anchor_asset">Anchor Asset:<span class="required">*</span></label>
                <input type="anchor_asset" name="anchor_asset" id="anchor_asset" placeholder="Name of  asset that serves as anchor">
              </div>

              <div class="form_element">
                <label for="attestation_of_reserve">Attestation Of Reserve(URL):<span class="required">*</span></label>
                <input type="text" name="attestation_of_reserve" id="attestation_of_reserve" placeholder="Link to proof of anchor asset and others ">
              </div>

              <div class="form_element">
                <label for="redemption_instructions">Redemption Instructions:<span class="required">*</span></label>
                <textarea type="text" name="redemption_instructions" id="redemption_instructions" placeholder="Instructions on how to redeem tokens"></textarea>
              </div>
            </section>

            <div class="form_element">
              <label for="image">Asset's image(logo):<span class="required">*</span></label>
              <input type="text" name="image" id="image" placeholder="Link to asset's image">
            </div>



            <div class="form_element">
              <label for="desc">Asset's description:<span class="required">*</span></label>
              <textarea type="text" name="desc" id="desc" placeholder="Asset's description here"></textarea>
            </div>

            <p class="err">Dead</p>
            <div class="form_controls">
              <button value="Prev"> Prev</button>
              <input type="submit" value="Preview">
            </div>


          </form>

        </div>
      </section>
    </main>
  <?php } ?>

  <?php if (isset($_GET['type']) && $_GET['type'] === "preview") {
    if (!isset($_GET['id']) && !isset($_SESSION['link'])) { ?>
      <p class="fileError">Asset Information Not Found...</p>
      <?php } else {
      $name = $_GET['id'];
      if (!isset($_GET['id'])) {
        $name = $_SESSION['link'];
      }
      $path = "../toml/" . $name;
      if (file_exists($path)) {
        $data = $dbConnect->real_escape_string(file_get_contents($path));


        $data = preg_replace('#(\\\r\\\n|\\\n)#', "<br>", $data);
        $data = preg_replace('#(\\\)#', "", $data);
      ?>
        <main id="preview">
          <div class="top">
            <h3>Download Asset Information</h3>
            <button class="download">
              <a href="<?php echo $path ?>" download="stellar.toml">
                <i class="fa-solid fa-download" title="Download toml file"></i>
              </a>

            </button>
            <a href="<?php echo $path ?>" download="stellar.toml" hidden class="hiddenFileLink">
              <i class="fa-solid fa-download" title="Download toml file"></i>
            </a>
          </div>

          <article class="content">
            <?php echo $data ?>
          </article>
        </main>
      <?php
      } else { ?>
        <p class="fileError"> Asset Information File Does Not Exist...</p>
  <?php }
    }
  }
  ?>
  </main>
  <script src=" ../js/jquery.js" defer></script>
  <script src=" ./js/asset_info.js" defer></script>
</body>

</html>