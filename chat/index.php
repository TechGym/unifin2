<?php
include 'includes/functions.inc.php';
include '../includes/functions.php';

if (!isset($loggedInfo)) {
  header("Location: ../");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, minimum-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache , no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>UNIFIN - Chat</title>
  <link rel="shortcut icon" href="./images/logo.png" type="image/png">
  <link rel="stylesheet" href="./css/inc.css">
  <link rel="stylesheet" href="./css/chat_styles.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>


</head>

<body class="dark">
  <main>
    <div class="includes">
      <?php
      require_once "./includes/header.php";
      require_once "./includes/menu.php";
      ?>

    </div>
    <section id="container">
      <section id="chatArea">
        <div class="messageArea">
          <?php foreach ($messages as $message) {
            $chat = new ChatController();
            $chat->setController($message['sender_id'], $message['message_id'], $message['timestamp'], $message['message'], $message);
            $countryFlag = $chat->fetchSenderCountryFlag();
            $firstname = $chat->fetchSenderName();
            $moderator_status = $chat->fetchModStatus();
            $content = $chat->formatMessage();
            $time = $chat->getTime();
            $comments = $chat->fetchComments();
            $commentDesc = $chat->createCommentDescription($comments);
          ?>
            <?php if ($message['sender_id']  == $loggedInfo['user_id']) { ?>
              <article class="chatItem sent" data-id="<?php echo $message['message_id'] ?>">
                <div class="author">
                  <div class="user" data-user-id="<?php echo $message['sender_id'] ?>">You</div>
                  <div class="country">
                    <img src="<?php echo $countryFlag ?>" alt="Country Flag">
                  </div>
                  <div class="moderator"></div>
                </div>

                <div class="message"> <?php echo ($content) ?></div>
                <div class="timestamp"><?php echo $time ?></div>
                <div class="comments">
                  <div class="comment">
                    <?php if (!$comments) { ?>
                      <div class="chat">
                        <i class="fa-regular fa-comment"></i>
                      </div>
                      <p>Leave a comment</p>
                    <?php } else { ?>
                      <span><?php echo $commentDesc ?> </span>
                    <?php } ?>
                  </div>
                  <div class="arrow" id="<?php echo $message['message_id'] ?>">
                    <i class=" fa-solid fa-arrow-right"></i>
                  </div>
                </div>
              </article>

            <?php } else { ?>
              <article class="chatItem received" data-id="<?php echo $message['message_id'] ?>">
                <div class=" author">
                  <div class="user" data-user-id="<?php echo $message['sender_id'] ?>"><?php echo $firstname ?></div>
                  <div class="country">
                    <img src="<?php echo $countryFlag ?>" alt="Country Flag">
                  </div>
                  <div class="moderator">
                  </div>
                </div>

                <div class="message"> <?php echo $message['message'] ?></div>
                <div class="timestamp"><?php echo $time ?></div>
                <div class="comments">
                  <div class="comment">
                    <?php if (!$comments) { ?>
                      <div class="chat">
                        <i class="fa-regular fa-comment"></i>
                      </div>
                      <p>Leave a comment</p>
                    <?php } else { ?>
                      <span><?php echo $commentDesc; ?>

                      <?php } ?>
                  </div>
                  <div class="arrow" id="<?php echo $message['message_id'] ?>">
                    <i class="fa-solid fa-arrow-right"></i>
                  </div>
                </div>
              </article>
            <?php } ?>
          <?php } ?>
        </div>

      </section>
      <section class="formSection">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="chatControls">
          <textarea name="message" id="message"></textarea>
          <button class="send">
            <i class="fa-solid fa-paper-plane"></i>
          </button>
        </form>
      </section>
    </section>
  </main>
  <!-- View message component -->
  <aside id="message">
    <div class="top">
      <button class="close">X</button>
    </div>

    <div class="content">
    </div>
    <section class="replyMessage">
      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="chatControls">
        <textarea name="reply" id="reply"></textarea>
        <button class="sendReply">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </form>
    </section>

  </aside>

  <aside id="tip">
    <div class="container">
      <p>Send a tip to user with id of <span class="author"></span></p>
      <form action="" id="tipForm">
        <label for="tipAmount">Enter Tip Amount <span>(in ePHI)</span> </label>
        <input type="text" name="tipAmount" id="tipAmount" placeholder="00.00">
        <p class="displayNotice">An error occurred</p>
        <div class="control">
          <input type="submit" value="Send Tip">
          <button class="cancel">Cancel</button>
        </div>
      </form>
    </div>

  </aside>
  <script>
    function userData() {
      let data = {
        sender_id: "<?php echo $loggedInfo['user_id'] ?>",
        firstname: "<?php echo $loggedInfo['firstname'] ?>",
        sender_email: "<?php echo $loggedInfo['email'] ?>",
        countryFlag: "<?php echo loggedUserCountryFlag() ?> ",
        modStatus: "<?php echo setModStatus(); ?>",
      }
      return data;
    }
  </script>
  <script src="./js/chat.js" type="module" defer> </script>
</body>