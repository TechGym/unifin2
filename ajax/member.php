<?php
include "../includes/functions.php";
include "../functions/index.func.php";
if (isset($_POST["setMembership"])) {
    becomeAMember($loggedInfo['email']);
}
