<?php
include "includes/functions.php";
// Redirect to homepage when not a member 
statusRedirect();
$usersCouncil = checkUsersCouncil($loggedInfo['email']);

if ($usersCouncil !== '5' && $usersCouncil !== '50') {
  header("Location: ./");
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
  <title>UNIFIN - Admin Page</title>
  <link rel="shortcut icon" href="./images/logo.png" type="image/png">
  <link rel="stylesheet" href="./css/includes.css">
  <link rel="stylesheet" href="./css/vp-admin.css">
  <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
  <?php
  include "includes/notifications.php";

  ?>
  <main>
    <div class="includes">
      <?php include "includes/header.php";
      include "includes/menu.php"; ?>
    </div>
    <div id="container">
      <section id="proposals">
        <h3 class="section__title">Proposals</h3>
        <div class="section__elements">
          <a class="section__element" href="./proposals?type=new" target="_blank">
            <div class="element__icon"><i class="fa-solid fa-plus"></i></div>
            <div class="element__title">Create A Proposal</div>
          </a>
          <a class="section__element" href="./proposals" target="_blank">
            <div class="element__icon"><i class="fa-solid fa-eye"></i> </div>
            <div class="element__title">View Proposals</div>
          </a>
          <a class="section__element" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Confirm Proposal Completion</div>
          </a>

        </div>

      </section>
      <section id="assets">
        <h3 class="section__title">Assets</h3>
        <div class="section__elements">
          <a class="section__element" href="./unifin-admin/create_asset" target="_blank">
            <div class="element__icon">
              <i class="fa-solid fa-plus"></i>
            </div>
            <div class="element__title">Create An Asset</div>
          </a>
          <a class="section__element" href="./unifin-admin/asset_info" target="_blank">
            <div class="element__icon">
              <i class="fa-solid fa-file"></i>
            </div>
            <div class="element__title">Create Asset Info</div>
          </a>
        </div>

      </section>
      <section id="payments">
        <h3 class="section__title">Send Tips</h3>
        <div class="section__elements">
          <a class="section__element" href="./unifin-admin/send_tip?type=council5" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Tip Council of 5 Members </div>
          </a>
          <a class="section__element" href="./unifin-admin/send_tip?type=council50" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Tip Council of 50 Members </div>
          </a>
          <a class="section__element" href="./unifin-admin/send_tip?type=individual" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Tip An Individual </div>
          </a>
          <a class="section__element" href="./unifin-admin/send_tip?type=complete_team" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Tip Users With +5 referrals</div>
          </a>
          <a class="section__element" href="./unifin-admin/send_tip?type=all" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Tip All Members</div>
          </a>
        </div>


      </section>

      <section id="metrics">
        <h3 class="section__title">Sprint Metrics</h3>
        <div class="section__elements">
          <a class="section__element" href="./unifin-admin/metrics?type=sprint_members" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Sprint Members Detail </div>
          </a>
          <a class="section__element" href="./unifin-admin/metrics?type=incomplete" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Incomplete Team </div>
          </a>
          <a class="section__element" href="./unifin-admin/metrics?type=teamAverageTime" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Average Time For Team Recruitment</div>
          </a>
          <a class="section__element" href="./unifin-admin/metrics?type=ageRange" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Members In An Age Range</div>
          </a>
          <a class="section__element" href="./unifin-admin/metrics?type=logTimes" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Login Times</div>
          </a>
          <a class="section__element" href="./unifin-admin/metrics?type=genderCount" target="_blank">
            <div class="element__icon"></div>
            <div class="element__title">Gender Count</div>
          </a>
        </div>


      </section>
    </div>


  </main>
  <?php require_once "includes/footer.php" ?>
  <script src="./js/vp-admin.js" defer type="module"></script>
</body>

</html>