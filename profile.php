<?php
include "includes/functions.php";
include "functions/profile.func.php";
// Redirect to homepage when not a member 
statusRedirect();
$usersCouncil = checkUsersCouncil($loggedInfo['email']);

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
    <title>UNIFIN - Profile</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/profile.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
    <?php
    /*
    If it is not set $_GET["type"] user sees the profile page else see a specific form
    Forms in includes/forms.php
    */
    ?>
    <?php if (!isset($_GET['type'])) { ?>
        <?php
        include "includes/notifications.php";


        ?>
        <main class="<?php echo navigationClass() ?>">
            <section class="includes">
                <?php include "includes/header.php"; ?>
                <?php include "includes/menu.php"; ?>
            </section>

            <section id="container">
                <article id="balance">
                    <h3><span>Balance: </span>
                        <?php
                        echo $currentBalance;
                        ?> ePHI
                    </h3>
                </article>

                <section id="card">
                    <h3 class="name">
                        <?php echo (fetchFullName($loggedInfo["email"])) ?>
                    </h3>
                    <div class="council">
                        <?php $usersCouncil = checkUsersCouncil($loggedInfo['email']); ?>
                        <?php if ($usersCouncil == "5") { ?>
                            <p>Council of 5</p>
                            <p>Council of 50</p>
                        <?php } elseif ($usersCouncil == "50") { ?>
                            <p>Council of 50</p>
                        <?php } ?>
                    </div>
                    <div class="address">
                        <div class="eth">
                            <img src="./images/stellar.jpg" alt="Stellar">
                        </div>
                        <p>Address</p>
                    </div>
                </section>
                <section id="details">
                    <p>Account Details</p>
                    <div class="detail">
                        <div class="keyword">Email:</div>
                        <div class="value"><?php echo strtolower($loggedInfo['email']) ?></div>
                    </div>
                    <div class="detail">
                        <div class="keyword">Country:</div>
                        <div class="value"><?php echo ucwords($loggedInfo['country']) ?></div>
                    </div>

                    <?php if (isset($loggedInfo['phone']) && strtolower($loggedInfo['country']) === "united states") { ?>
                        <div class="detail">
                            <div class="keyword">Phone:</div>
                            <div class="value"> <?php echo $loggedInfo['phone'] ?></div>
                        </div>
                    <?php } ?>
                    <?php if (isset($loggedInfo['dob'])) { ?>
                        <div class="detail">
                            <div class="keyword">DOB:</div>
                            <div class="value"><?php echo formatDate($loggedInfo['dob']) ?></div>
                        </div>
                    <?php } ?>

                </section>

                <section id="settings">

                    <article class="verifications">
                        <?php
                        //  Fetching from the verifications table  
                        $verifications = fetchVerificationsStatus();
                        ?>
                        <p>Account Verification</p>
                        <?php if (isset($loggedInfo['phone']) && strtolower($loggedInfo['country']) === "united states") { ?>
                            <div class="phone">
                                <p>Phone number </p>
                                <div class="status">
                                    <?php if ($verifications['phone_status'] == "unverified") {
                                    ?>
                                        <a class="addBtn " href="./profile?type=phone">Add</a>
                                    <?php } else {
                                    ?>
                                        <div class="verified">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                    <?php  }
                                    ?>

                                </div>
                            </div>
                        <?php } ?>

                        <div class="dob">
                            <p>Date Of Birth </p>
                            <div class="status">
                                <?php if (empty($loggedInfo['dob'])) { ?>
                                    <a class="addBtn " href="./profile?type=dob">Add</a>
                                <?php } else { ?>
                                    <div class="verified">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                <?php  }  ?>

                            </div>
                        </div>
                        <div class="email">
                            <p>Email </p>
                            <div class="status">
                                <div class="verified">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            </div>
                        </div>

                    </article>
                    <article class="report">
                        <p>Report Compromised Account</p>
                        <a href="./reportcompromised">Report</a>
                    </article>

                </section>
                <?php $proposals = fetchLoggedUsersProposals(); ?>

                <section id="edit" class="<?php if (count($proposals) > 0) {
                                                echo 'active';
                                            } ?>">
                    <p>Edit Your Information</p>
                    <div class="content">
                        <article class="name">
                            <p>Full name</p>
                            <a href="./profile?type=name">Edit</a>
                        </article>
                        <?php
                        if (!empty($loggedInfo['dob'])) { ?>
                            <article class="dob">
                                <p>DOB</p>
                                <a href="./profile?type=dob"> Edit</a>
                            </article>
                        <?php } ?>
                        <article class="email">
                            <p>Email</p>
                            <a href="./profile?type=email"> Edit</a>
                        </article>

                        <?php if (strtolower($loggedInfo['country']) === "united states") {
                            if ($verifications['phone_status'] != "unverified") { ?>
                                <article class="phone">
                                    <p>Phone</p>
                                    <a href="./profile?type=phone">Edit</a>
                                </article>
                            <?php } ?>
                        <?php } ?>

                        <article class="address">
                            <p>Address</p>
                            <a href="./profile?type=address"> Edit</a>
                        </article>
                    </div>
                    <a href="./changepassword" class="password">
                        change Password
                    </a>

                </section>

                <?php if (count($proposals) > 0) { ?>
                    <section id="proposals">

                        <h3>Your Proposals</h3>
                        <div class="heading">
                            <h3>Status</h3>
                            <h3>Proposal</h3>
                        </div>
                        <div class="content">
                            <div class="proposals_container">
                                <?php

                                foreach ($proposals as $proposal) {
                                ?>
                                    <div class="content_elem">
                                        <div class="status ">
                                            <div class="status_container <?php echo determineStatus($proposal['status'], $proposal['proposal_id']) ?>">
                                                <i class="fa-solid "></i>
                                            </div>
                                        </div>
                                        <div class="proposal">
                                            <div class="proposal_heading"><?php echo truncateText($proposal['heading'], 30) ?></div>
                                            <div class="proposal_desc"><?php echo truncateText($proposal['description'], 120) ?></div>
                                            <div class="controls">
                                                <a href="./proposals?id=<?php echo $proposal['proposal_id'] ?>" class="view_proposal">View Proposal</a>
                                                <?php $status = determineStatus($proposal['status'], $proposal['proposal_id']);
                                                if ($status == "disapproved" || $status == 'completed') { ?>
                                                    <button class="removeProposal" id="<?php echo $proposal['proposal_id'] ?>">Remove Proposal</button>
                                                <?php } ?>
                                            </div>
                                        </div>

                                    </div>

                                <?php } ?>
                            </div>
                            <?php if (count($proposals) == 4) { ?>
                                <div class="seeMore">
                                    See More
                                </div>
                            <?php } ?>
                        </div>
                    </section>
                <?php } ?>

            </section>

        </main>
    <?php } else { ?>
        <?php include "includes/forms.php" ?>
    <?php } ?>
    <script src="./js/profile.js" type="module" defer></script>
</body>

</html>