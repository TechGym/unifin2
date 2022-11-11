<?php
include "includes/config.php";
$params = array();

$requestURL = 'https://restcountries.com/v3.1/all';

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

$response = @json_decode($response, true);
// var_dump($response);

foreach ($response as $res) {
  $name = $res['name']['common'];
  $flag = $res['flags']['png'];

  echo $name . "  " . $flag;

  $sql = "INSERT INTO countries(country_name , link_to_flag) VALUES ('$name' , '$flag')";
  // $dbConnect->query($sql);
}
