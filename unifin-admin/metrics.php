<?php
require_once "../includes/functions.php";
require_once "../functions/admin/general.func.php";

statusRedirect();
if (!checkCouncilOf5($loggedInfo['email'])) {
  header("Location: ../");
};

$type = $_GET['type'];
if (!$type) {
  header("Location: ../admin");
}

// Check if type exists
$types = ["sprint_members", "incomplete", "teamaveragetime", "agerange", "logtimes", "gendercount"];
if (!isset($type) || !in_array(strtolower($type), $types)) {
  header("Location: ../admin");
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
  <title>UNIFIN - Metrics</title>
  <link rel="shortcut icon" href="../images/logo.png" type="image/png">
  <link rel="stylesheet" href="../css/includes.css">
  <link rel="stylesheet" href="../css/metrics.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
  <main class="container">
    <h3>Sprint Information</h3>
    <?php
    if ($type === "sprint_members") { ?>
      <p class="totalMembers ">Total Members = <?php echo fetchTotalMembers() ?></p>
      <?php $sprints = memberCount(); ?>
      <table>
        <tr>
          <th>Sprint</th>
          <th>Total Members</th>
        </tr>
        <?php foreach ($sprints as $key => $value) { ?>
          <tr>
            <td><?php echo (int) $key + 1 ?></td>
            <td><?php echo $value['current_users']; ?></td>
          </tr>
        <?php } ?>
      </table>
    <?php } ?>
    <?php if ($type === "incomplete") {
      $incompleteTeamMembers = prepareDueTimeMembers();
    ?>
      <p class="description">Users with an incomplete team </p>
      <p>Total Members : <span><?php echo count($incompleteTeamMembers) ?></span></p>
      <?php if (count($incompleteTeamMembers) > 0) { ?>
        <table>
          <tr>
            <th>User Id</th>
            <th>User Email</th>
            <th>Team Members Left</th>
          </tr>
          <?php foreach ($incompleteTeamMembers as $incompleteTeamMember) { ?>
            <tr>
              <td><?php echo $incompleteTeamMember['user_id'] ?></td>
              <td><?php echo $incompleteTeamMember['email'] ?></td>
              <td><?php echo 5 - (int) checkReferralCountOfUserWithIncompleteTeam($incompleteTeamMember['user_id']) ?></td>
            </tr>
          <?php } ?>
        </table>
      <?php } ?>


    <?php } ?>
    <?php if ($type === "teamAverageTime") { ?>
      <p class="totalMembers ">Total average recruitment time</p>
      <table>
        <tr>
          <th>Total Users </th>
          <th>Total Time</th>
          <th>Average Time</th>
        </tr>
        <tr>
          <?php
          $membersCount = count(fetchCompMembers());
          $totalTime = calculateTotalTimeForCompleteTeams();

          ?>
          <td><?php echo $membersCount  ?></td>
          <td><?php echo $totalTime ?> seconds</td>
          <td><?php echo calculateAverageTime($totalTime, $membersCount) ?> seconds</td>
        </tr>

      </table>

    <?php }  ?>
    <?php if ($type === "ageRange") { ?>
      <p>Total Members : <span><?php echo fetchTotalMembers(); ?></span> </p>
      <p>Members in a certain age range </p>
      <table>
        <tr>
          <th>Age range</th>
          <th>Total Members</th>
        </tr>
        <td> 1-11 </td>
        <td><?php echo fetchAgeRange(0, 11) ?> </td>
        </tr>
        <tr>
          <td> 12-20 </td>
          <td><?php echo fetchAgeRange(11, 20) ?> </td>
        </tr>
        <tr>
          <td> 21-30 </td>
          <td><?php echo fetchAgeRange(20, 30) ?> </td>
        </tr>
        <tr>
          <td> 31-45 </td>
          <td><?php echo fetchAgeRange(30, 45) ?> </td>
        </tr>
        <tr>
          <td> 46-70</td>
          <td><?php echo fetchAgeRange(45, 70) ?> </td>
        </tr>
        <tr>
          <td> 71-90</td>
          <td><?php echo fetchAgeRange(70, 90) ?> </td>
        </tr>
        <tr>
          <td> 91-120</td>
          <td><?php echo fetchAgeRange(90, 120) ?> </td>
        </tr>

      </table>

    <?php }  ?>
    <?php if ($type === "genderCount") { ?>
      <p>Total Members : <span><?php echo fetchTotalMembers(); ?></span> </p>
      <p>Gender count of users</p>
      <table>
        <tr>
          <th>Males</th>
          <th>Females</th>
        </tr>
        <tr>
          <td> <?php echo fetchTotalGender("male"); ?></td>
          <td><?php echo fetchTotalGender("female"); ?> </td>
        </tr>
      </table>

    <?php }  ?>
    <?php if ($type === "logTimes") { ?>
      <p>Total Members : <span><?php echo fetchTotalMembers(); ?></span> </p>
      <p>Total Login times of users : <span><?php echo userLogInTimes() ?></span></p>


    <?php }  ?>


  </main>


  <script src="./js/metrics.js" defer type="module"></script>
</body>


</html>