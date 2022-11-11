<?php
session_start();
$dbServerName = "localhost";
$dbUsername = "root";
$dbPassword = "Asare4ster...";
$dbName = "unifi";
$userInfo = "";

$dbConnect = new mysqli($dbServerName, $dbUsername, $dbPassword, $dbName);
