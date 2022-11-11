<?php
if (file_exists("../functions/notifications.func.php")) {
  include_once "../functions/notifications.func.php";
} else {
  include_once "./functions/notifications.func.php";
}
$notifications = createNotifications();
?>

<aside id="notifications">
  <div class="top">
    <button class="left closeNotification">X</button>
    <div class="notifications">Notifications</div>
  </div>

  <?php if (count($notifications) > 0) { ?>
    <section class="content">
      <?php foreach ($notifications as $notification) { ?>
        <article class="notice <?php echo $notification['status'] ?>" id="<?php echo $notification['id'] ?>" data-type="<?php echo $notification['type'] ?>">
          <a href="<?php echo $notification['link'] ?>">
            <div class="heading">
              <div class="image">
                <img src="./images/<?php echo $notification['src'] ?>" alt="">
              </div>

              <?php echo $notification['heading'] ?>
            </div>
            <div class="desc">
              <?php echo $notification['message'] ?>
              <div class="timestamp">
                <?php echo $notification['timestamp'] ?>
              </div>
            </div>

          </a>
        </article>
      <?php } ?>
    </section>
  <?php } else { ?>
    <p>You do not have any notifications </p>
  <?php } ?>
</aside>