<?php
include "includes/functions.php";
// Redirect to homepage when not a member 
statusRedirect();


$userId = $loggedInfo['user_id'];
// Fetching member referrals of loggedUser
$referrals = fetchMemberReferrals($userId);
$count = count($referrals);

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
    <title>UNIFIN - Dashboard </title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/dashboard.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
    <?php include "includes/notifications.php" ?>

    <main class="guest">
        <article class="includes">
            <?php include "includes/header.php" ?>
            <?php include "includes/menu.php" ?>
        </article>
        <section id="container">
            <!-- The hero section -->
            <section id="hero">
                <div class="modal_container">
                    <div class="hero__modal">
                        <div class="modal">
                            <div class="modal__item">
                                <div class="modal__image">
                                    <img src="./images/logo.png" alt="">
                                </div>
                            </div>
                            <div class="modal__item">
                                <div class="modal__image">Image2 here</div>
                            </div>
                            <div class="modal__item">
                                <div class="modal__image">Image3 here</div>
                            </div>
                            <div class="modal__item">
                                <div class="modal__image">Image4 here</div>
                            </div>
                            <div class="modal__item">
                                <div class="modal__image">Image5 here</div>
                            </div>
                        </div>

                    </div>
                    <div class="indicators">
                        <div class="indicator active"></div>
                        <div class="indicator"></div>
                        <div class="indicator"></div>
                        <div class="indicator"></div>
                        <div class="indicator"></div>
                    </div>
                </div>
                <div class="notifications">
                    <div class="icon">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="content">
                        <div class="notification_item">
                            Change these ones
                        </div>
                        <div class="notification_item">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit quo eos perferendis
                        </div>
                        <div class="notification_item">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit quo eos perferendis.
                        </div>
                        <div class="notification_item">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit quo eos perferendis.
                        </div>
                        <div class="notification_item">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit quo eos perferendis.
                        </div>
                    </div>
                </div>
                <!-- Available commands on screen -->
                <div class="hero__actions">
                    <div class="referral">
                        <div class="description">
                            <?php
                            if ($count >= 5) {
                                echo 5;
                            } else {
                                echo $count;
                            }
                            ?>/5 <span>- Referrals</span>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="chat">
                        <div class="description">
                            <span>chat</span>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-message"></i>
                        </div>
                    </div>
                    <div class="percentage">
                        <div class="description">
                            <?php
                            // Using current sprint which is set in functions.php
                            ?>
                            <?php echo createSprintPercentage(); ?> <span> - Sprint <?php echo fetchCurrentSprint(); ?></span>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                    </div>

                </div>
            </section>

        </section>

    </main>
    <?php include "includes/controls.php" ?>

    <script src=" ./js/dashboard.js" type="module" defer></script>
</body>

</html>