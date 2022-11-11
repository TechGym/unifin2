<?php
include "includes/functions.php";
include "functions/referrals.func.php";
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
    <title>UNIFIN - Referrals</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/referral.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
</head>

<body class="dark">
    <?php
    include "includes/notifications.php";

    $id = $loggedInfo['user_id'];

    ?>
    <main>
        <div class="includes">
            <?php
            include "includes/header.php";
            include "includes/menu.php";
            ?>
        </div>
        <section id="container">
            <div class="top">
                <h3 class="referral_team">Referral Team</h3>
                <div class="referral_count">
                    <div class="icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="description">
                        <?php echo displayReferrals() ?>
                    </div>
                </div>
            </div>


            <div class="referPeople">
                <?php $referralCount = (int) $totalReferrals;
                if ($referralCount < 5) { ?>
                    <?php if (getRemainingTime() > 0) { ?>
                        <p>
                            Unlock all your membership awards by recruiting <span><?php echo 5 - $referralCount  ?> </span> member(s) within the next <span><?php echo  getRemainingTime(); ?> days</span>
                        </p>
                    <?php } else { ?>
                        <p>
                            You have elapsed the time of <span> 30 </span>days that was given to you. A total of <span><?php echo 5 - $referralCount  ?></span> members will be assigned to you although
                            you can still refer more users as bonus referrals
                        </p>
                    <?php } ?>
                <?php } else { ?>
                    <p>
                        You have reached your milestone of <span> 5 member referrals</span>. Get more rewards by referring more member(s).
                    </p>
                <?php } ?>
                <?php $status = fetchStatus($loggedInfo["email"]);
                if ($status == "member" || $status == "pool") { ?>
                    <div class="referralLink">
                        <div class="icon clipboard" title="Copy to clipboard">
                            <i class="fa-regular fa-clipboard"></i>
                        </div>
                        <p class=""> Here's your referral link <span class="note">(click to copy)</span>:<span class="link">
                                https://www.unifin.cc?ref=<?php echo fetchReferralCode() ?>
                            </span>
                        <p>
                    </div>

                <?php } ?>
                With each referral who becomes a member, you will be awarded <span>75 ePHI </span> tokens.
                After achieving a successful Referral Team of 5/5 , awards for additional membership referrals are possible.
                See <a href="./faq">FAQ </a> for more details
                </p>

            </div>

            <?php
            $i = 1;
            $referrals = mergeReferrals();
            if (count($referrals) > 0) {
            ?>
                <div id="referrals">
                    <div class="heading">
                        <div class="index">
                            Index
                        </div>
                        <div class="name">
                            Full name
                        </div>
                        <div class="status">
                            Stat
                        </div>
                    </div>
                    <div class="content">
                        <?php foreach ($referrals as $ref) {
                            $memberEmail = $ref["user_email"];
                        ?>
                            <div class="referral">
                                <div class="index">
                                    <?php echo $i ?>
                                </div>
                                <div class="name">
                                    <?php echo fetchFullName($memberEmail) ?>
                                </div>
                                <div class="status">
                                    <?php echo ucwords(fetchStatus($memberEmail)); ?>
                                </div>
                            </div>
                            <?php $i++; ?>
                        <?php } ?>
                    </div>
                </div>
                <?php $i++ ?>
            <?php  } else {  ?>
                <div class="noRef">
                    <p class="noRef">You currently do not have any Referrals</p>
                </div>
            <?php  }  ?>
            <?php include "includes/controls.php" ?>
        </section>


    </main>



    <script src="./js/referral.js" type="module" defer></script>
</body>

</html>