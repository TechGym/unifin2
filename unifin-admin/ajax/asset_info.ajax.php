<?php
require_once "../../includes/config.php";
require_once "../../stellar/functions.stellar.php";


function clearWhiteSpaces($text) {
  global $dbConnect;
  $text = mysqli_real_escape_string($dbConnect, $text);
  return preg_replace('#(\\\r\\\n|\\\n)#', "\n", $text);
}
function createStringFromProperty($key, $values, $tag) {
  $string = $tag . " ";
  foreach ($values as $key => $value) {
    $string .= $key . '="' . clearWhiteSpaces($value) . "\"\n ";
  }
  return $string;
}

function createFile($content, $key) {
  $name = "stellar" . time() . ".toml";
  $myfile = fopen("../../toml/" . $name, "w") or die("Unable to open file!");;
  fwrite($myfile, $content);
  fclose($myfile);

  $response = [
    $key => true,
    "link" => $name
  ];
  return json_encode($response);
}
if (isset($_POST["CURRENCIES"]) && isset($_POST["ACCOUNTS"])) {
  $data = "";
  foreach ($_POST as $key => $values) {
    if ($key === "ACCOUNTS") {
      $string = "ACCCOUNTS=[";
      foreach ($values as $value) {
        $string .= "\"" . trim($value) . "\",";
      }
      $string = substr($string, 0, strlen($string) - 1);
      $string .= "] " . "";
      $data .= $string . "\n";
    }
    if ($key === "DOCUMENTATION" || $key === "CURRENCIES" || $key === "PRINCIPALS") {
      $tag = "\n" . "[" . $key . "]" . "\n";
      if ($key === "CURRENCIES" || $key === "PRINCIPALS") {
        $tag = "[[" . $key . "]]" . "\n";
      }
      $data .= createStringFromProperty($key, $values, $tag) . "\n";
    }
  }
  $default = file_get_contents("../../toml/default.toml");
  $data =  $default . "\n" . "\n" . $data;


  // Work with file here
  $name = "stellar" . time() . ".toml";


  $myfile = fopen("../../toml/" . $name, "w") or die("Unable to open file!");;
  fwrite($myfile, $data);
  fclose($myfile);

  $response = [
    "success" => true,
    "link" => $name
  ];
  $_SESSION['link'] = $response['link'];
  echo json_encode($response);
}


if (isset($_POST['delete'])) {
  $name = $_POST['filename'];

  $path = "../../toml/" . $name;
  $response = ['deleted' => true];
  if (!unlink($path)) {
    echo "An error occurred";
  } else {
    echo json_encode($response);
  }
}
