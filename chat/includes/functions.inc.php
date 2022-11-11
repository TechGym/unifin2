<?php
require "./classes/Dbh.class.php";
require "./classes/Models/General.model.php";
require "./classes/Views/General.view.php";
require "./classes/Models/Chat.model.php";
require "./classes/Views/Chat.view.php";
require "./classes/Controllers/Chat.controller.php";


$generalView = new GeneralView();
$cookieName  = md5('logUser');

if (isset($_COOKIE[$cookieName])) {
  $loggedInfo = $generalView->getUserFromCookie($_COOKIE[$cookieName]);
  $generalView->setGeneralView($loggedInfo);
  $messages = $generalView->fetchAllMessages();
}
