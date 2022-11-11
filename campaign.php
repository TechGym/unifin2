<?php
include "includes/functions.php";
include "functions/campaign.func.php";
// Redirect to homepage when not a member 
statusRedirect();
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
    <title>UNIFIN - Campaign</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/campaign.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>


</head>

<body class="dark">
    <?php include "includes/notifications.php" ?>

    <main>
        <div class="includes">
            <?php include "includes/header.php" ?>
            <?php include "includes/menu.php" ?>
        </div>
        <section id="container">
            <h3>Campaign</h3>
            <section class="info">

                <article class="info__item">
                    <p>Total Referrals: <span><?php echo count(fetchMemberReferrals($loggedInfo["user_id"])) ?></span></p>
                </article>
                <article class="info__item">
                    <p>Bonus Referrals:<span> <?php echo count(fetchBonusReferrals($loggedInfo["user_id"]));  ?></span> </p>
                </article>
                <article class="info__item">
                    <p>Team Awards:
                        <span>
                            <?php echo fetchBalance($loggedInfo["user_id"], $loggedInfo["email"])['team'] ?> ePHI
                        </span>

                    </p>
                </article>
                <article class="info__item">
                    <p>Bonus Awards: <span> <?php echo fetchBalance($loggedInfo["user_id"], $loggedInfo["email"])['awards'] ?> ePHI</span></p>
                </article>
                <article class="info__item">
                    <p>Total Tips Received: <span> <?php echo fetchBalance($loggedInfo["user_id"], $loggedInfo["email"])['tips'] ?> ePHI</span></p>
                </article>
                <article class="info__item">
                    <p>Total Tips Sent: <span> <?php echo fetchBalance($loggedInfo["user_id"], $loggedInfo["email"])['deductions'] ?> ePHI</span></p>
                </article>
            </section>
            <section class="currentSprint">
                <p>Current Sprint: <span><?php echo fetchCurrentSprint() ?></span></p>

                <!-- <p>Remaining Days: <span><?php // echo checkRemainingSprintTime(fetchCurrentSprint()) 
                                                ?></span></p> -->
            </section>
            <section class="sprints">
                <p class="total">Total Sprints: <span>9</span></p>
                <div class="info">
                    <p>Members : <span>19,531,250</span></p>
                    <p>Treasury : <span>$488,281,250</span></p>
                </div>
                <div class="image">
                    <!-- <canvas id="staticGraph" width="80vw" height="70vw" aria-label="Sprints information"></canvas> -->
                    <img src="./images/Chart.jpg" alt="Chart">
                </div>
            </section>
            <section class="description">
                <p>
                    Unifin's low-cost referral-based campaigns unlock huge growth and financial potential.
                    Members become life-time benefactors of the UNIFI trust
                </p>
                <p>
                    See <a href="./whitepaper">White Paper</a> under Open-Source Economics
                </p>
            </section>
        </section>

    </main>
    <script src="./js/campaign.js" type="module" defer></script>
</body>