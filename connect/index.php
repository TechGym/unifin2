<?php
require_once "../includes/functions.php";

// Checking if user is loggedIn
if (!isset($loggedInfo)) {
	header("Location: ../login");
}

// Redirect to dashboard if user is already a member 
$status = fetchStatus($loggedInfo['email']);
if ($status === "pool" || $status === "member") {
	header("Location: ../dashboard");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<link rel="icon" href="./static/media/logo.1aae3e058abb5d72fb3f.png" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<meta name="theme-color" content="#000000" />
	<meta name="description" content="Web site created using create-react-app" />
	<title>UNIFIN - Become A Member </title>
	<script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
	<script defer="defer" src="./static/js/main.f9beab19.js"></script>
	<link href="./static/css/main.3cb0a181.css" rel="stylesheet" />
</head>

<body class="dark" style="background: #130d34">
	<noscript>You need to enable JavaScript to run this app.</noscript>
	<div id="root"></div>
</body>

</html>