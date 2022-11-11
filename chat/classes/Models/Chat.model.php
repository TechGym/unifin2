<?php
class ChatModel extends Dbh {

  protected function getSender($id) {
    $sql = "SELECT country , email , firstname , user_id FROM users WHERE user_id = ?";
    $stmt = $this->connectDB()->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  protected function getCountryFlag($sender_id) {
    $country = $this->getSender($sender_id)['country'];
    $sql = "SELECT link_to_flag FROM countries WHERE country_name = '$country'";
    $stmt = $this->connectDB()->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC)['link_to_flag'];
  }

  protected function fetchCommentsOfMessage($message_id, $skip) {
    $sql = "SELECT * FROM messages WHERE type = 'comment' && comment_to = '$message_id'  ORDER BY timestamp DESC LIMIT $skip , 150 ";
    $stmt = $this->connectDB()->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  protected function storeChatMessage($message, $sender_id, $timestamp) {
    $sql = "INSERT INTO messages (message, sender_id , timestamp) VALUES (?, ?, ?)";
    $stmt = $this->connectDB()->prepare($sql);
    return $stmt->execute([$message, $sender_id, $timestamp]);
  }

  protected function storeReplyMessage($message, $sender_id, $timestamp, $baseMessageId) {
    $sql = "INSERT INTO messages (message, sender_id , timestamp , type  , comment_to) VALUES (?, ?, ? , 'comment' , ?)";
    $stmt = $this->connectDB()->prepare($sql);
    return $stmt->execute([$message, $sender_id, $timestamp, $baseMessageId]);
  }

  protected function fetchLastMessageId($message, $sender_id, $timestamp) {
    $sql = "SELECT message_id FROM messages WHERE sender_id = '$sender_id' && message = '$message' && timestamp = '$timestamp' ORDER BY timestamp DESC LIMIT 1  ";
    $stmt = $this->connectDB()->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC)['message_id'];
  }

  protected function getModeratorStatus($sender_id) {
    $email = $this->getSender($sender_id)['email'];
    $sql = "SELECT council FROM membership WHERE user_email = '$email'";
    $stmt = $this->connectDB()->query($sql);
    $council =  $stmt->fetch(PDO::FETCH_ASSOC)['council'];
    return $council === "5" || $council === "50" ? true : false;
  }

  protected function getComments($message_id) {
    $sql = "SELECT * FROM messages WHERE type = 'comment' &&  comment_to = '$message_id'";
    $stmt = $this->connectDB()->query($sql);
    $comment =  $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $comment;
  }

  protected function fetchMoreChats($start) {
    $sql = "SELECT * FROM messages WHERE type = 'new' ORDER BY timestamp ASC LIMIT $start , 250 ;";
    $stmt = $this->connectDB()->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  protected function getBaseMessage($id) {
    $sql = "SELECT * FROM messages WHERE message_id = '$id' ;";
    $stmt = $this->connectDB()->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }
  protected function fetchUserBalance($id) {
    $sql = "SELECT total FROM tokens WHERE user_id = '$id' ;";
    $stmt = $this->connectDB()->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }

  protected function insertTipTransaction($amount, $receiver, $timestamp, $sender_email) {
    $type = 'tip';
    $sql = "INSERT INTO transactions (amount, receiver , timestamp , sender_email , type) VALUES (?, ?, ? , ? , ?)";
    $stmt = $this->connectDB()->prepare($sql);
    return $stmt->execute([$amount, $receiver, $timestamp, $sender_email, $type]);
  }

  // Category table holds the sender of the tip
  protected function insertTipNotification($author_id, $amount,  $timestamp, $sender_email) {
    $type = 'tip';
    $link = "./profile";
    $sql = "INSERT INTO notifications (user_id , amount ,timestamp , sender_email , link , type) VALUES (?, ?, ? , ? , ? , ?)";
    $stmt = $this->connectDB()->prepare($sql);
    return $stmt->execute([$author_id, $amount, $timestamp, $sender_email, $link, $type]);
  }
  protected function updateSenderTokens($sender_id, $amount) {
    $amount = (float) $amount;
    $sender = "UPDATE tokens SET total = total - $amount , deductions = deductions + $amount WHERE user_id = ? ";
    $stmt =  $this->connectDB()->prepare($sender);
    return $stmt->execute([$sender_id]);
  }
  protected function updateReceiverTokens($receiver_id, $amount) {
    $amount = (float) $amount;
    $sender = "UPDATE tokens SET total = total + $amount , tips = tips + $amount WHERE user_id = ? ";
    $stmt = $this->connectDB()->prepare($sender);
    return $stmt->execute([$receiver_id]);
  }
}
