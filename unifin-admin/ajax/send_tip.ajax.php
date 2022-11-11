<?php
require_once "../../includes/functions.php";
require_once "../../functions/notifications.func.php";
require_once "../../functions/admin/general.func.php";
require_once "../../functions/admin/send_tip.func.php";


if (isset($_POST['type'])) {
  $target = htmlspecialchars($_POST['type']);
  $amount = htmlspecialchars($_POST['amount']);

  $type = 'tip';

  $members = fetchTarget($target);


  foreach ($members as $member) {
    // Update tokens
    updateTokens($member['email'], $amount);
  }
  // Insert notification
  $tip = insertTip($target, $amount);

  // Insert transaction
  $total = (int) count($members) * (float) $amount;

  $transaction = insertTransaction($target, $total, $loggedInfo['email'], $type);

  echo "success";
}
