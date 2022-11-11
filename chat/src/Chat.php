<?php

namespace MyApp;

use ChatController;
use ChatModel;
use ChatView;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR .  'classes' .  DIRECTORY_SEPARATOR .  'Dbh.class.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR .  'classes' . DIRECTORY_SEPARATOR .  'Models'  . DIRECTORY_SEPARATOR .  'Chat.model.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR .  'classes'  . DIRECTORY_SEPARATOR .  'Controllers'  . DIRECTORY_SEPARATOR .  'Chat.controller.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR .  'classes'  . DIRECTORY_SEPARATOR .  'Views'  . DIRECTORY_SEPARATOR .  'Chat.view.php';

require dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR .  'includes'   . DIRECTORY_SEPARATOR .  'functions.php';


function storeData($data) {
  $chat = new ChatController();
  $message = $data['message'];
  $timestamp = $data['date'];
  $senderId = $data['sender_id'];
  $chat->setMessage($message);
  $chat->setTimestamp($timestamp);
  $chat->setSenderId($senderId);
  return $chat->storeMessage();
}

function createData($data) {
  $chat = new ChatController();
  $chat->setController($data['sender_id'], $data['message_id'], $data['timestamp'], $data['message'], $data);
  $countryFlag = $chat->fetchSenderCountryFlag();
  $firstname = $chat->fetchSenderName();
  $moderator_status = $chat->fetchModStatus();
  $content = $chat->formatMessage();
  $time = $chat->getTime();
  $comments = $chat->fetchComments();
  $commentDesc = $chat->createCommentDescription($comments);

  return [
    'firstname' => $firstname,
    'moderatorStatus' => $moderator_status,
    'content' => $content,
    "time" => $time,
    "commentsDesc" => $commentDesc,
    "countryFlag" => $countryFlag,
    "messageId" => $data['message_id'],
  ];
}

function fetchMoreMessages($start) {
  $chat = new ChatController();
  return $chat->getMoreChats($start);
}

function commentsCount($senderId) {
  $chat = new ChatController();
  $chat->setSenderId($senderId);
  return $chat->fetchComments();
}

function fetchLastMessageId($data) {
  $chat = new ChatController();
  $message = $data['message'];
  $timestamp = $data['date'];
  $senderId = $data['sender_id'];
  $chat->setMessage(($message));
  $chat->setTimestamp($timestamp);
  $chat->setSenderId(($senderId));
  return $chat->getLastMessageId();
}

function getCommentsOfMessage($id, $skip) {
  $chat = new ChatController();
  $chat->setMessageId($id);
  $comments = $chat->getCommentsOfMessage($skip);
  return $comments;
}

class Chat implements MessageComponentInterface {
  protected $clients;

  public function __construct() {
    $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn) {

    $chatView = new \ChatView();

    // Store the new connection in $this->clients
    $this->clients->attach($conn);
    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $data = json_decode($msg, true);



    if (isset($data['type']) && $data['type'] === "newMessage") {
      $chat = new ChatController();
      $data['date'] = gmdate(("Y-m-d H:i:s"));
      $data['timestamp'] = formatTime(gmdate("H:i:s"));
      $result =  storeData($data);
      $data['messageId'] = fetchLastMessageId($data);

      foreach ($this->clients as $client) {

        if ($from == $client) {
          $data['from'] = 'Me';
        }
        if ($result) {
          $client->send(json_encode($data));
        } else {
          echo $result;
        }
      }
      return;
    }
    if ($data['type'] === "fetchMore") {
      $skip = $data['start'];
      $more = fetchMoreMessages($skip);
      $data = [];
      if (count($more) === 0) {
        $data = ['content' => "ended"];
      } else {
        foreach ($more as $content) {
          $data[] = createData($content);
        }
        $data = ["content" => "more", "data" => $data];
      }
      foreach ($this->clients as $client) {
        if ($from == $client) {
          $client->send(json_encode($data));
        }
      }
      return;
    }
    if (isset($data['type']) && $data['type'] === "fetchComments") {
      $chat = new ChatController();
      $chat->setMessageId($data['id']);
      $baseMessage = $chat->fetchBaseMessage();
      $baseMessage = createData($baseMessage);
      $comments = getCommentsOfMessage($data['id'], "0");
      $commentsData = [];
      foreach ($comments as $comment) {
        $commentsData[] = createData($comment);
      }
      if (count($comments) === 0) {
        $data = ["type" => "fetchComments", "baseMessage" => $baseMessage, "comments" => false];
      } else {
        $data = ["type" => "fetchComments", "baseMessage" => $baseMessage, "comments" => $commentsData];
      }
      foreach ($this->clients as $client) {
        if ($from == $client) {
          $client->send(json_encode($data));
        }
      }
      return;
    }
    if ((isset($data['type']) && $data['type'] === "fetchMoreComments")) {
      $chat = new ChatController();
      $chat->setMessageId($data['message_id']);
      $comments = getCommentsOfMessage($data['message_id'], $data['start']);
      $commentsData = [];
      foreach ($comments as $comment) {
        $commentsData[] = createData($comment);
      }
      if (count($comments) === 0) {
        $data = ["type" => "fetchMoreComments",  "comments" => false];
      } else {
        $data = ["type" => "fetchMoreComments", "comments" => $commentsData];
      }
      foreach ($this->clients as $client) {
        if ($from == $client) {
          $client->send(json_encode($data));
        }
      }
      return;
    }
    if (isset($data['type']) && $data['type'] === "reply") {
      $chat = new ChatController();
      $data['date'] = gmdate(("Y-m-d H:i:s"));
      $data['timestamp'] = formatTime(gmdate("H:i:s"));
      $chat->setMessage($data['message']);
      $chat->setTimestamp($data['date']);
      $chat->setSenderId($data['sender_id']);
      $chat->setBaseId($data['baseMessageId']);

      $result =  $chat->storeReply($data);
      $data['messageId'] =  $chat->getLastMessageId();

      // Fetching the commentDesc of the base Message to be updated on the main chat page
      $chat->setMessageId($data['baseMessageId']);
      $comments = $chat->fetchComments();

      $data['commentDesc'] = $chat->createCommentDescription($comments);

      foreach ($this->clients as $client) {

        if ($from == $client) {
          $data['from'] = 'Me';
        }
        if ($result) {
          $client->send(json_encode($data));
        } else {
          echo $result;
        }
      }
      return;
    }
    if (isset($data['type']) && $data['type'] === "newTip") {
      $chat = new ChatController();
      var_dump($data);
      $chat->setSenderId($data['sender_id']);
      $balance = $chat->getUserBalance();
      $timestamp = gmdate(("Y-m-d H:i:s"));

      // Check if sender's balance is enough
      if ($balance && (int) $balance <= (int) $data['amount']) {
        $response = ['type' => 'tip',  "error" => "Insufficient funds"];
        foreach ($this->clients as $client) {
          if ($from == $client) {
            $client->send(json_encode($response));
          }
        }
        return;
      }

      // var_dump($data);
      // Check if receiver  exists
      $transaction = $chat->storeTipTransaction($data['amount'], $data['receiver_id'], $timestamp, $data['sender_email']);
      $notification = $chat->storeTipNotification($data['receiver_id'], $data['amount'], $timestamp, $data['sender_email']);
      $transfer = $chat->storeTransfer($data['receiver_id'], $data['amount']);


      if ($transaction && $notification && $transfer) {
        foreach ($this->clients as $client) {
          if ($from == $client) {
            $client->send(json_encode(['type' => 'tip', 'success' => 'true']));
          }
        }
      } else {
        $response = ['type' => 'tip',  "error" => "An error occurred"];
        foreach ($this->clients as $client) {
          if ($from == $client) {
            $client->send(json_encode($response));
          }
        }
        return;
      }
    }
  }

  public function onClose(ConnectionInterface $conn) {
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
  }
}
