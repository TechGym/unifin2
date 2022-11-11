<?php

if (isset($_POST['token'])) {
  $token = $_POST['token'];
  $params = array();
  $params['secret'] = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // Secret key
  $params['response'] = urlencode($token);
  $params['remoteip'] = $_SERVER['REMOTE_ADDR'];

  $params_string = http_build_query($params);
  $requestURL = 'https://www.google.com/recaptcha/api/siteverify?' . $params_string;

  // Get cURL resource
  $curl = curl_init();

  // Set some options
  curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $requestURL,
  ));

  // Send the request
  $response = curl_exec($curl);
  // Close request to clear up some resources
  curl_close($curl);

  try {
    $response = @json_decode($response, true);
    if (isset($response) && $response['success']) {
      // Set session
      session_start();
      $_SESSION['recaptchaActive'] = $token;
      echo "success";
    }
  } catch (Error $e) {
    echo "Token is either expired or invalid";
  }
}
