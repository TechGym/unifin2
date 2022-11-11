<?php
require __DIR__ . '/vendor/autoload.php';
require_once "vendor/paragonie/sodium_compat/autoload.php";

use Soneso\StellarSDK\Asset;
use PHPUnit\Framework\TestCase;
use Soneso\StellarSDK\SEP\Toml\Currency;
use Soneso\StellarSDK\SEP\Toml\StellarToml;
use Soneso\StellarSDK\SEP\Toml\Validator;
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

$sdk = StellarSDK::getTestNetInstance();

/**
 * Create a new stellar account
 * 
 * @returns void
 */
function createAnAccount() {
  $keyPair = KeyPair::random();
  $privateKey = $keyPair->getSecretSeed();
  $publicKey = $keyPair->getAccountId();
  echo $privateKey;
  echo "<br>";
  echo "<br>";
  echo $publicKey;
};


/**
 * Fund a test account on stellar
 */
function fundTestAccount() {
  global $publicKey1, $publicKey2;
  $funded1 = FriendBot::fundTestAccount($publicKey1);
  $funded2  = FriendBot::fundTestAccount($publicKey2);
  print($funded1 ? "account 1 funded" : "account not funded");
  print($funded2 ? "account 2 funded" : "account not funded");
}

function fundAcc($key) {
  $funded1 = FriendBot::fundTestAccount($key);
  print($funded1 ? "account 1 funded" : "account not funded");
}

/**
 * Request  account data.
 * 
 * Request account data of a particular public key
 * @param String
 * @return void
 *  */
function requestAccountData($publicKey) {
  global  $sdk;
  $account = $sdk->requestAccount($publicKey);

  // You can check the `balance`, `sequence`, `flags`, `signers`, `data` etc.
  // Loop through array returned from calling getBalances and check the assert type to display balance appropriately
  foreach ($account->getBalances() as $balance) {
    switch ($balance->getAssetType()) {
      case Asset::TYPE_NATIVE:
        printf(PHP_EOL . "Balance: %s XLM", $balance->getBalance());
        echo "<br>";
        echo "<br>";
        break;
      default:
        printf(
          PHP_EOL . "Balance: %s %s",
          $balance->getBalance(),
          $balance->getAssetCode(),
        );
        echo "<br>";
        echo "<br>";
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

function sendXLM($key) {
  $sdk = StellarSDK::getTestNetInstance();
  $senderKeyPair = KeyPair::fromSeed($key);
  $destination = "GBXGIK5FQNTIZEJBSCD7ME353TIYEKFU6BZMSLVYTACXX4NCIAS6EPPY";

  // Load sender account data from the stellar network.
  $sender = $sdk->requestAccount($senderKeyPair->getAccountId());

  // Build the transaction to send 100 XLM native payment from sender to destination
  $paymentOperation = (new PaymentOperationBuilder($destination, Asset::native(), "100"))->build();
  $transaction = (new TransactionBuilder($sender))->addOperation($paymentOperation)->build();

  // Sign the transaction with the sender's key pair.
  $transaction->sign($senderKeyPair, Network::testnet());

  // Submit the transaction to the stellar network.
  $response = $sdk->submitTransaction($transaction);
  if ($response->isSuccessful()) {
    print(PHP_EOL . "Payment sent");
  }
}

function createACustomToken($issuerKey, $distributorKey, $limit, $assetCode) {
  global $sdk;
  $issuerKeyPair = KeyPair::fromSeed($issuerKey);
  $distributorKeyPair = KeyPair::fromSeed($distributorKey);

  $issuerId  = $issuerKeyPair->getAccountId();
  $distributorId = $distributorKeyPair->getAccountId();

  $issuerAccount =  $sdk->requestAccount($issuerId);
  $distributorAccount =  $sdk->requestAccount($distributorId);

  try {
    // // Create a custom Asset using issuer account
    $astroDollar = new AssetTypeCreditAlphaNum12($assetCode, $issuerId);

    // Trustline 
    $cto = (new ChangeTrustOperationBuilder($astroDollar, $limit))->build();
    $transaction = (new TransactionBuilder($distributorAccount))->addOperation($cto)->build();
    $transaction->sign($distributorKeyPair, Network::testnet());
    $sdk->submitTransaction($transaction);

    // // Load the trustor account again to see if the trustline has been created.
    $distributorAccount =  $sdk->requestAccount($distributorId);

    // // Check if the trustline exists.
    foreach ($distributorAccount->getBalances() as $balance) {

      if ($balance->getAssetCode() == $assetCode) {
        //     // Sending tokens to trustline
        $issuer = $sdk->requestAccount($issuerId);

        $paymentOperation = (new PaymentOperationBuilder($distributorId, $astroDollar, $limit))->build();
        $transaction = (new TransactionBuilder($issuer))->addOperation($paymentOperation)->build();

        // The issuer signs the transaction.
        $transaction->sign($issuerKeyPair, Network::testnet());

        // Submit the transaction.
        $sdk->submitTransaction($transaction);

        $check = floatval($limit) - 1;

        $receiver = $sdk->requestAccount($distributorId);
        foreach ($receiver->getBalances() as $balance) {
          if (
            $balance->getAssetCode() == $assetCode
          ) {
            return "success";
          }
        }
      }
    }
  } catch (Error $e) {
    echo "An error occured during creation of asset";
  }
}



// requestAccountData("GCUIY4R2ZNQB7AUWDG3P56FYQJNPTEJPVXVPXWESWW3RNAG6XUE5FZXO");

/**
 * Request Asset Data From A Domain
 * 
 * @param String
 * @return void
 *  */
function readDataFromADomain($domain) {
  $stellarToml = StellarToml::fromDomain($domain);
  return $stellarToml;
}

function readDataFromAString($string) {
  $stellarToml = new StellarToml($string);
  return $stellarToml;
}
