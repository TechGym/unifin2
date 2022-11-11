<?php

class ChatView extends ChatModel {
  private $username;


  public function setUsername($username) {
    $this->username = $username;
  }
  public function getUsername() {
    return $this->username;
  }
}
