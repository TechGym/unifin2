<?php
require __DIR__ . '/vendor/autoload.php';
require_once "vendor/paragonie/sodium_compat/autoload.php";


use Soneso\StellarSDK\Asset;
use Soneso\StellarSDK\Crypto\StrKey;
use Soneso\StellarSDK\AssetTypeCreditAlphanum4;
use Soneso\StellarSDK\AssetTypeCreditAlphaNum12;
use Soneso\StellarSDK\ChangeTrustOperationBuilder;
use Soneso\StellarSDK\CreateAccountOperationBuilder;
use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\ManageDataOperationBuilder;
use Soneso\StellarSDK\ManageSellOfferOperationBuilder;
use Soneso\StellarSDK\MuxedAccount;
use Soneso\StellarSDK\Network;
use Soneso\StellarSDK\PathPaymentStrictReceiveOperationBuilder;
use Soneso\StellarSDK\PathPaymentStrictSendOperationBuilder;
use Soneso\StellarSDK\PaymentOperationBuilder;
use Soneso\StellarSDK\Responses\Operations\CreateAccountOperationResponse;
use Soneso\StellarSDK\Responses\Operations\PaymentOperationResponse;
use Soneso\StellarSDK\StellarSDK;
use Soneso\StellarSDK\TransactionBuilder;
use Soneso\StellarSDK\Util\FriendBot;

// Creating a new account 
// function createAnAccount() {
//   $keyPair = KeyPair::random();
//   $privateKey = $keyPair->getSecretSeed();
//   $publicKey = $keyPair->getAccountId();
//   echo $privateKey;
//   echo "<br>";
//   echo "<br>";
//   echo $publicKey;
// };

$publicKey1 = "GDZIX3ILRUH4M242FNHRLKDVR72PGFKYLIXWQXYICF2MZOYPUVQL7WK4";
$publicKey2 = "GCUIY4R2ZNQB7AUWDG3P56FYQJNPTEJPVXVPXWESWW3RNAG6XUE5FZXO";
$publicKey3 = "GDU6AUJKAJLD76NMIAX5YH56BECPE6FDA47CFQNZWOQCWVJXYDG6J7ZI";

$privateKey1 = "SAFM52SKE47UBONVMCNKKNQCHHALLBNAKLFQUDPUXW4V2IZLHXTZUDTG";
$privateKey2 = "SCBWDMLIJOW5GRXFUJ2JM76YVIV6F5GDJU4445WBZH45SS54BYNTY3BV";
$privateKey3 = "SDHOBX5CCFIMOZ7N7ZOJNC6UC5WA2FVMF3TK4THVIX6LGHZFTSXCAERB";

$sdk = StellarSDK::getTestNetInstance();

// Funding account 
// function fundTestAccount() {
//   global $publicKey1, $publicKey2;
//   $funded1 = FriendBot::fundTestAccount($publicKey1);
//   $funded2  = FriendBot::fundTestAccount($publicKey2);
//   print($funded1 ? "account 1 funded" : "account not funded");
//   print($funded2 ? "account 2 funded" : "account not funded");
// }


// Check account 

// Request the account data.
function requestAccountData() {
  global  $sdk, $publicKey2;
  $account = $sdk->requestAccount($publicKey2);

  // You can check the `balance`, `sequence`, `flags`, `signers`, `data` etc.
  // Loop through array returned from calling getBalances and check the assert type to display balance appropriately
  foreach ($account->getBalances() as $balance) {
    switch ($balance->getAssetType()) {
      case Asset::TYPE_NATIVE:
        printf(PHP_EOL . "Balance: %s XLM", $balance->getBalance());
        break;
      default:
        printf(
          PHP_EOL . "Balance: %s %s",
          $balance->getBalance(),
          $balance->getAssetCode(),
        );
    }
  }

  // // Accounts sequence number 
  // print(PHP_EOL . "Sequence number: " . $account->getSequenceNumber());


  // // Information regarding signer 
  // foreach ($account->getSigners() as $signer) {
  //   var_dump($signer);
  //   print(PHP_EOL . "Signer public key: " . $signer->getKey());
  // }
}


// Checking for payments and these will actually be stored in the database to track the current payment to start from 
// function checkPayments() {
//   global $sdk, $publicKey1;
//   $operationsPage = $sdk->payments()->forAccount($publicKey1)->order("desc")->execute();

//   foreach ($operationsPage->getOperations() as $payment) {
//     var_dump($payment);
//     echo "<br>";
//     echo "<br>";
//     if ($payment->isTransactionSuccessful()) {
//       printf(PHP_EOL . "Transaction hash: " . $payment->getTransactionHash());
//     }
//   }
// }


// Sending native asset to individuals 
// function sendNativeAssets() {
//   global $sdk, $privateKey1, $publicKey2;
//   $senderKeyPair = KeyPair::fromSeed($privateKey1);
//   $destination = $publicKey2;

//   // Load sender account data from the stellar network.
//   $sender = $sdk->requestAccount($senderKeyPair->getAccountId());


//   // // Build the transaction to send 10 XLM native payment from sender to destination
//   $paymentOperation = (new PaymentOperationBuilder($destination, Asset::native(), "10"))->build();
//   $transaction = (new TransactionBuilder($sender))->addOperation($paymentOperation)->build();


//   // // Sign the transaction with the sender's key pair.
//   $transaction->sign($senderKeyPair, Network::testnet());

//   // // Submit the transaction to the stellar network.
//   $response = $sdk->submitTransaction($transaction);
//   if ($response->isSuccessful()) {
//     print(PHP_EOL . "Payment sent");
//   }
// }

function createACustomToken($privateKey1, $privateKey2, $limit, $assetCode) {
  global $sdk,  $publicKey1, $publicKey2;
  $distributorAccount =  $sdk->requestAccount($publicKey2);
  $issuerAccount =  $sdk->requestAccount($publicKey1);
  $trustorKeypair = KeyPair::fromSeed($privateKey1);
  $issuerKeyPair = KeyPair::fromSeed($privateKey1);

  // // Create a custom Asset using issuer account
  $assetCode = "Foster";
  $astroDollar = new AssetTypeCreditAlphaNum12($assetCode, $publicKey2);

  // Create the trustline. Limit: $limit Assets.
  $cto = (new ChangeTrustOperationBuilder($astroDollar, $limit))->build();
  $transaction = (new TransactionBuilder($distributorAccount))->addOperation($cto)->build();
  $transaction->sign($trustorKeypair, Network::testnet());
  $sdk->submitTransaction($transaction);

  // Load the trustor account again to see if the trustline has been created.
  $distributorAccount =  $sdk->requestAccount($publicKey1);

  // Check if the trustline exists.
  foreach ($distributorAccount->getBalances() as $balance) {

    if ($balance->getAssetCode() == $assetCode) {
      print(PHP_EOL . "Trustline for " . $assetCode . " found. Limit: " . $balance->getLimit());
      // Trustline for ASTRO found. Limit: 10000.0
      break;

      // Sending tokens to trustline
      $issuer = $sdk->requestAccount($publicKey1);

      $paymentOperation = (new PaymentOperationBuilder($publicKey2, $astroDollar, $limit))->build();
      $transaction = (new TransactionBuilder($issuer))->addOperation($paymentOperation)->build();

      // The issuer signs the transaction.
      $transaction->sign($issuerKeyPair, Network::testnet());

      // Submit the transaction.
      $sdk->submitTransaction($transaction);
    }
  }
}
function sendCustomTokens() {
  global $privateKey1, $privateKey2, $privateKey3;
  $sdk = StellarSDK::getTestNetInstance();

  // Create the key pairs of issuer, sender and receiver from their secret seeds. We will need them for signing.
  $issuerKeyPair = KeyPair::fromSeed($privateKey3);
  $senderKeyPair = KeyPair::fromSeed($privateKey1);
  $receiverKeyPair = KeyPair::fromSeed($privateKey2);

  // Account Ids.
  $issuerAccountId = $issuerKeyPair->getAccountId();
  $senderAccountId = $senderKeyPair->getAccountId();
  $receiverAccountId = $receiverKeyPair->getAccountId();

  // Define the custom asset/token issued by the issuer account.
  $ePHIAsset = new AssetTypeCreditAlphaNum4("ePHI", $issuerAccountId);

  // Prepare a change trust operation so that we can create trustlines for both, the sender and receiver.
  // Both need to trust the IOM asset issued by the issuer account so that they can hold the token/asset.
  // Trust limit is 10000.
  $chOp = (new ChangeTrustOperationBuilder($ePHIAsset, "1000000"))->build();

  // Load the sender account data from the stellar network so that we have it's current sequence number.
  $sender = $sdk->requestAccount($senderAccountId);

  // Build the transaction for the trustline (sender trusts custom asset).
  $transaction = (new TransactionBuilder($sender))->addOperation($chOp)->build();
  $transaction->sign($senderKeyPair, Network::testnet());
  $sdk->submitTransaction($transaction);

  // Load the receiver account so that we have it's current sequence number.
  $receiver = $sdk->requestAccount($receiverAccountId);

  // Build the transaction for the trustline (receiver trusts custom asset).
  $transaction = (new TransactionBuilder($receiver))->addOperation($chOp)->build();
  $transaction->sign($receiverKeyPair, Network::testnet());
  $sdk->submitTransaction($transaction);

  // Load the issuer account so that we have it's current sequence number.
  $issuer = $sdk->requestAccount($issuerAccountId);

  // // Send all tokens ePHI non native payment from issuer to sender.
  $paymentOperation = (new PaymentOperationBuilder($senderAccountId, $ePHIAsset, "50000"))->build();
  $transaction = (new TransactionBuilder($issuer))->addOperation($paymentOperation)->build();

  // The issuer signs the transaction.
  $transaction->sign($issuerKeyPair, Network::testnet());

  // Submit the transaction.
  $sdk->submitTransaction($transaction);

  // The sender now has 500 IOM and can send to the receiver.
  // Send 200 IOM (non native payment) from sender to receiver.
  // $paymentOperation = (new PaymentOperationBuilder($senderAccountId, $ePHIAsset, "7999"))->build();
  // $transaction = (new TransactionBuilder($issuer))->addOperation($paymentOperation)->build();

  // // The sender signs the transaction.
  // $transaction->sign($issuerKeyPair, Network::testnet());

  // // Submit the transaction to stellar.
  // $sdk->submitTransaction($transaction);

  // // Check that the receiver obtained the 200 IOM.
  // $receiver = $sdk->requestAccount($receiverAccountId);
  // foreach ($receiver->getBalances() as $balance) {
  //   if (
  //     $balance->getAssetType() != Asset::TYPE_NATIVE
  //     && $balance->getAssetCode() == "ePHI"
  //     && floatval($balance->getBalance()) > 1999
  //   ) {
  //     print("received IOM payment");
  //     break;
  //   }
  // }
}

function modifyTrustline() {
  // Now, let's modify the trustline, change the trust limit to 40000.
  // $limit = "0";

  // // Build the operation.
  // $cto = (new ChangeTrustOperationBuilder($astroDollar, $limit))->build();

  // // Build the transaction.
  // $transaction = (new TransactionBuilder($distributorAccount))->addOperation($cto)->build();

  // // Sign the transaction.
  // $transaction->sign($trustorKeypair, Network::testnet());

  // // Submit the transaction to stellar.
  // $sdk->submitTransaction($transaction);

  // // Load the trustor account again to see if the trustline has been created.
  // $distributorAccount =  $sdk->requestAccount($publicKey1);

  // // Check if the trustline exists.
  // foreach ($distributorAccount->getBalances() as $balance) {
  //   if ($balance->getAssetCode() == $assetCode) {
  //     print(PHP_EOL . "Trustline for " . $assetCode . " found. Limit: " . $balance->getLimit());
  //     // Trustline for ASTRO found. Limit: 40000.0
  //     break;
  //   }
  // }
}
?>
<!-- <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/event-stream" charset="utf-8">
  <title>Document</title>
</head>

<body>
  <script src="./jquery.js" defer></script>
  <script src="./app.js" defer></script>
</body>

</html> -->