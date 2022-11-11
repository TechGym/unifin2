<?php
include_once "../includes/functions.php";
include_once "../functions/index.func.php";
include_once "../functions/notifications.func.php";

$currentUsers = fetchTotalMembers();
echo $currentUsers;
