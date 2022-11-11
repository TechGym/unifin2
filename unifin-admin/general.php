<?php
require_once "../includes/functions.php";
require_once "../functions/admin/general.func.php";
require_once "../functions/notifications.func.php";



if (!isset($loggedInfo)) {
  header("Location: ../login");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
</head>

<body>

  <div class="sprints">
    <h3>Sprint Information</h3>
    <p>Total Members = <?php echo fetchTotalMembers() ?></p>
    <?php $sprints = memberCount();
    foreach ($sprints as $key => $value) { ?>
      <div>Sprint -<?php echo (int) $key + 1 ?> : <span><?php echo $value['current_users']; ?> members</span></div>
    <?php }
    ?>
  </div>
  <div class="dueTimeMembers">
    <h3 style="margin-top: 30px;">Members with overdue time </h3>
    <?php $dueTimeMembers = prepareDueTimeMembers();
    foreach ($dueTimeMembers as $member) {
      $fullName = fetchFullName($member['email']);
      echo ($fullName); ?>

    <?php } ?>
  </div>

  <div class="averageTime" style="margin-top: 10px;">
    <h3>Average referral time</h3>
    <!-- <?php // echo (calculateAverageTime()); 
          ?> seconds -->
  </div>

  <div class="totalGender" style="margin-top: 40px;">
    <h3>Gender Count</h3>
    <p>Males : <span><?php echo fetchTotalGender("male"); ?></span></p>
    <p>Females : <span><?php echo fetchTotalGender("female"); ?></span></p>
  </div>


  <div class="ageRange">
    <h3>Number of users in a certain age range </h3>
    <p>12 -25 years <span>: <?php echo fetchAgeRange(12, 25); ?></span> members</p>
    <p>20 -50 years <span>: <?php echo fetchAgeRange(20, 50); ?></span> members </p>
  </div>

  <div class="distributedTokens">
    <p style="font-weight: bold;">Distributed tokens : <span><?php echo number_format(createDistributedTokens()); ?></span></p>
  </div>

  <div class="userLogintimes">
    <p>Number of tmes users logged into the app</p>
    <p>Logged times : <?php echo userLogInTimes(); ?></p>
  </div>


</body>

</html>