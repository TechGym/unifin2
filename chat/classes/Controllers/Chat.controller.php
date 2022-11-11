<?php

class ChatController extends ChatModel {
  private $sender_id;
  private $message_id;
  private $timestamp;
  private $message;
  private $data;
  private $base_id;
  public function setController($sender_id, $message_id, $timestamp, $message, $data) {
    $this->sender_id = htmlspecialchars($sender_id);
    $this->message_id = htmlspecialchars($message_id);
    $this->timestamp = htmlspecialchars($timestamp);
    $this->data = $data;
    $this->message = htmlspecialchars($message);
  }

  public function setSenderId($sender_id) {
    $this->sender_id = htmlspecialchars($sender_id);
  }

  public function setBaseId($baseId) {
    $this->base_id = htmlspecialchars($baseId);
  }


  public function setMessageId($message_id) {
    $this->message_id = htmlspecialchars($message_id);
  }

  public function getCommentsOfMessage($skip) {
    return $this->fetchCommentsOfMessage($this->message_id, $skip);
  }
  public function setTimestamp($timestamp) {
    $this->timestamp = htmlspecialchars($timestamp);
  }
  public function setMessage($message) {
    $this->message = ($message);
  }
  public function fetchSenderCountryFlag() {
    return $this->getCountryFlag($this->sender_id);
  }
  public function formatNumber($number) {
    $number = (int) $number;
    return $number >= 10 ? $number : "0" . $number;
  }

  public function storeMessage() {
    return $this->storeChatMessage($this->message, $this->sender_id, $this->timestamp);
  }

  public function getLastMessageId() {
    return $this->fetchLastMessageId($this->message, $this->sender_id, $this->timestamp);
  }

  public function formatMessage() {
    return nl2br(htmlentities($this->message, ENT_QUOTES, "UTF-8"));
  }

  public function storeReply() {
    return $this->storeReplyMessage($this->message, $this->sender_id, $this->timestamp, $this->base_id);
  }

  public function getTime() {
    $time = explode(" ", $this->timestamp)[1];
    $time = explode(":", $time);
    $hour = (int) $time[0];
    $minutes = $time[1];
    $seconds = $time[2];
    if ($hour > 12) {
      return $this->formatNumber($hour - 12) . ":" . $minutes . ":" . $seconds . " PM";
    }
    if ($hour === 12) {
      return $hour .  ":" . $minutes . ":" . $seconds . " PM";
    }
    return $this->formatNumber($hour) .  ":" . $minutes . ":" . $seconds . " AM";
  }
  public function fetchSenderName() {
    return $this->getSender($this->sender_id)['firstname'];
  }

  public function fetchModStatus() {
    return $this->getModeratorStatus($this->sender_id);
  }


  public function fetchComments() {
    return $this->getComments($this->message_id);
  }

  public function createCommentDescription($comments) {
    if ($comments) {
      $count = count($comments);
      $desc = (int) $count === 1 ? "comment" : "comments";
      return $count . " " . $desc;
    }
    return false;
  }

  public function fetchBaseMessage() {
    return $this->getBaseMessage($this->message_id);
  }

  public function getMoreChats($start) {
    return $this->fetchMoreChats($start);
  }

  public function getUserBalance() {
    return $this->fetchUserBalance($this->sender_id);
  }

  public function storeTipTransaction($amount, $receiver_id, $timestamp, $sender_email) {
    return $this->insertTipTransaction($amount, $receiver_id, $timestamp, $sender_email);
  }
  public function storeTipNotification($author_id, $amount,  $timestamp, $sender_email) {
    return $this->insertTipNotification($author_id, $amount,  $timestamp, $sender_email);
  }
  public function storeTransfer($receiver_id, $amount) {
    $sender =  $this->updateSenderTokens($this->sender_id, $amount);
    $receiver =  $this->updateReceiverTokens($receiver_id, $amount);
    return $receiver && $sender;
  }
}
