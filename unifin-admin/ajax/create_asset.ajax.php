<?php
require_once "../../includes/functions.php";
require_once "../../stellar/functions.stellar.php";
require_once "../../functions/admin/create_asset.func.php";

if (isset($_POST['asset_name']) && isset($_POST['asset_limit'])) {
  $type = strtolower(htmlspecialchars($_POST['type']));
  $assetName = ucwords(htmlspecialchars($_POST['asset_name']));
  $limit = ucwords(htmlspecialchars($_POST['asset_limit']));
  $domain = strtolower(htmlspecialchars($_POST['asset_domain']));
  $seedPhrase1 = htmlspecialchars($_POST['seedphrase1']);
  $seedPhrase2 = strtolower(htmlspecialchars($_POST['seedphrase2']));

  $date = gmdate("Y-m-d H:i:s");

  // Error Handling
  if (!$assetName || !$limit || !$seedPhrase1 || !$seedPhrase2) {
    echo "Please fill in all credentials";
  } else {
    // Validate inputs 
    if (validateName($assetName)) {
      if (validateLimit($limit)) {
        if (validateLimitMax($limit)) {
          if (validateURL($domain)) {
            if (validateSeedPhrase($seedPhrase1) && validateSeedPhrase($seedPhrase1)) {
              // Creating asset
              $response = "";
              $response = createACustomToken($seedPhrase1, $seedPhrase2,  $limit, $assetName);
              if ($response === "success") {
                if ($type === "exit") {
                  echo "successfully created, exiting ...";
                } else {
                  echo "successfully created, proceeding ...";
                }
              } else {
                echo "An error occurred during creation of asset";
              }
            } else {
              echo "Invalid seed phrase(s) format";
            }
          } else {
            echo "Please enter a valid domain name ";
          }
        } else {
          echo "Asset limit should be less than 9223372036854775807 ";
        }
      } else {
        echo "Invalid asset limit format";
      }
    } else {
      echo "Invalid asset name";
    }
  }
}
