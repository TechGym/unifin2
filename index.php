<?php
include "includes/functions.php";
include "functions/index.func.php";
/* 
1.  If user is logged in, redirect to the dashboard anytme they visit unifin.cc 
2.  /home to load the index page for user
*/
$currentURL = trim($_SERVER['REQUEST_URI']);
$currentURL = explode("/", $currentURL);
$currentURL = $currentURL[count($currentURL) - 1];
if (isset($loggedInfo) && $currentURL != "home") {
    header('Location: ./dashboard');
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
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="UNIFIN" />
    <meta name="twitter:account_id" content="1537024038278676480" />
    <meta name="twitter:creator" content="@scyrety" />
    <meta name="twitter:title" content="Register and become a member of UNIFIN" />
    <meta name="twitter:description" content="UNIFIN aims at being the first DAO that actually functions as one." />
    <meta name="twitter:image:src" content="https://unifin.cc/backdoor/images/logo.png" />
    <title>UNIFIN - Home</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/styles.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
    <?php if (isset($loggedInfo)) {
        include "includes/notifications.php";
    }
    ?>
    <?php include "includes/header.php" ?>
    <div class="container">
        <div class="animated">
            <img src="./images/logo.png" class="img_mobile" alt="" srcset="">
            <img src="./images/logo.png" alt="" srcset="">
            <img src="./images/logo.png" alt="" srcset="">
            <img src="./images/logo.png" alt="" srcset="">
            <img src="./images/logo.png" class="img_mobile" alt="" srcset="">
            <img src="./images/logo.png" alt="" srcset="">
            <img src="./images/logo.png" alt="" srcset="">
            <img src="./images/logo.png" alt="" srcset="">
            <img src="./images/logo.png" alt="" srcset="">
            <img src="./images/logo.png" alt="" srcset="">
            <img src="./images/logo.png" class="img_mobile" alt="" srcset="">
        </div>

        <?php include "includes/menu.php" ?>
        <div class="hero">
            <div class="image">
                <img src="./images/logo.png" alt="Hero">
            </div>
            <div class="intro">
                <h1>
                    The First Decentralized Autonomous Organization With ...
                </h1>
                <div class="no_whales">
                    No whales
                </div>
                <div class="no_coin_dumps">
                    No coin dumps
                </div>
                <p>
                    <span class="unifi"></span>As global elites and governments maneuver to implement a new digital monetary system termed “the Great Reset,” the world’s legacy monetary system seems likely to fall right off a cliff and take many of us with it. Unifin, endeavors to absorb the impact of this painful transition for its members through the creation of a decentralized autonomous organization, which uses a democratic governance system that allows member benefits to evolve over time and fund itself in a sustainable way through a visionary treasury system. <br><br>We, like Cardano, also want to “redistribute power from unaccountable structures and be a force for positive change and progress". As such, ePHI token awards are not distributed to individual investors, developers and/or founders. All members are equal, and every proposal is voted on by members prior to its execution. We are very proud to have built the first open-source economics platform that accumulates assets in a trustless manner to provide lifetime benefits and token rewards for our community of members.
                </p>
                <?php if (!isset($loggedInfo)) { ?>
                    <div class="ico">
                        <a href="./register"> Become a member</a>
                    </div>
                    <div class="ico ico2">
                        <a href="./login">Login</a>
                    </div>
                <?php } elseif (fetchStatus($loggedInfo["email"]) == "pending") {  ?>
                    <div class="ico " id="connect">
                        <a href="./connect"> Connect Wallet</a>
                    </div>
                <?php }; ?>
            </div>

        </div>
        <section id="services">
            <h1>
                What Do We Offer
            </h1>
            <p class="desc">
                Unifin($ePHI) development is on track to deliver the most robust platform for security, scalability and innovation.
            </p>
            <div class="services__container">
                <div class="service__card">
                    <img src="./images/wall.png" alt="decentralized">
                    <h3> Community Driven</h3>
                    <p>Our democratic governance system allows each member to have an impact over the strategic direction of the DAO.</p>
                </div>
                <div class="service__card">
                    <img src="./images/secure.png" alt="">
                    <h3>Affordable</h3>
                    <p>Only $25 dollars for a lifetime membership, which can be transfered, sold as a NFT, or given to a love one as an inheritance.</p>
                </div>
                <div class="service__card">
                    <img src="./images/analysis.png" alt="">
                    <h3>Financial Benefits</h3>
                    <p>Our nine (9), thirty (30) day, sprint campaigns, generate almost a half of a billion dollars of value to support increasing services and awards for our members.</p>
                </div>
                <div class="service__card">
                    <img src="./images/invest.png" alt="">
                    <h3>Token Rewards</h3>
                    <p>Recieve 25 ePHI tokens when you become a member, and earn 75 additional ePHI tokens for each referral that becomes a member.</p>
                </div>
                <div class="service__card">
                    <img src="./images/platform.png" alt="">
                    <h3>Trading Platform</h3>
                    <p>Ransom your membership on the open market. There are limited membership spots available, and with increasing benefits, people will pay you to be a part of our community.</p>
                </div>
                <div class="service__card">
                    <img src="./images/buy.png" alt="">
                    <h3>Growth</h3>
                    <p>Restoring equity to our members, the ePHI token is a value increasing stable coin backed by every asset placed within the Unifin Trust.</p>
                </div>
            </div>
        </section>
        <section class="tokenomics">
            <h1 class="title_text">Tokenomics</h1>
            <h2 class="tokenomics_desc">
                Our ePHI token will be asset backed

            </h2>

            <div class="stats">
                <div class="stat__card">
                    <div class="image">
                        <img src="./images/phi_png_bullet.png" alt="">
                    </div>
                    <h1>11.7B</h1>
                    <h3>MEMBER REWARDS</h3>
                </div>
                <div class="stat__card">
                    <div class="image">
                        <img src="./images/phi_png_bullet.png" alt="">
                    </div>
                    <h1>27B</h1>
                    <h3>MAX TOKENS</h3>
                </div>
                <div class="stat__card">
                    <div class="image">
                        <img src="./images/phi_png_bullet.png" alt="">
                    </div>
                    <h1>1B</h1>
                    <h3>PARTNERSHIPS</h3>
                </div>
                <div class="stat__card">
                    <div class="image">
                        <img src="./images/phi_png_bullet.png" alt="">
                    </div>
                    <h1>15B</h1>
                    <h3>LOCKED SUPPLY</h3>
                </div>

            </div>
        </section>

    </div>
    <?php include "includes/footer.php" ?>
    <script src="./js/app.js" type="module" defer></script>
</body>

</html>