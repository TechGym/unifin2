<?php
include "includes/functions.php";
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
    <title>UNIFIN - FAQ</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/faq.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>

</head>

<body class="dark">
    <?php if (isset($loggedInfo)) {
        include "includes/notifications.php";
    } ?>
    <?php include "includes/header.php" ?>
    <?php include "includes/menu.php" ?>
    <main class="container">
        <h2 class="title">Frequently Asked Questions</h3>
            <br>
            <br>
            <div class="questions">
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            What is UNIFIN Trust?
                        </div>
                    </article>
                    <article class="answer">
                        UNIFIN Trust is the holding entity for all the assets accumulated through the democratic voting process of its members. These asset remain perpetually in the Trust to provide lifetime benefits for members. For example, the Nobel Peace prize is money that is contained within a Trust, and every year those who meet the requirements can be elected to receive the prize money from the Trust.
                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            What is the Council of 5?
                        </div>
                    </article>
                    <article class="answer">
                        The Council of 5 are the first 5 members, which includes the founders of UNIFIN.

                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            What is the Council of 50?
                        </div>
                    </article>
                    <article class="answer">
                        The Council of 50 are the first 50 members who join UNIFIN.

                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            What do the Council of 5 and 50 do?
                        </div>
                    </article>
                    <article class="answer">
                        The Council of 5 are the founders of UNIFIN who submit opportunities focused on growing member benefits. They submit proposals to the Council o 50 to either approve or reject them. These proposals must be vetted and approved by the Council of 50. Once approved, they are submitted to all members to be voted on prior to the execution of the proposal.
                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            Do any of the Council of 5 or 50 get special incentives or token rewards and/or paid salaries from UNIFIN?
                        </div>
                    </article>
                    <article class="answer">
                        No.
                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            Can members submit proposals?
                        </div>
                    </article>
                    <article class="answer">
                        Yes, by sending an email to proposals@unfin.cc
                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            What are the risk of becoming a member?
                        </div>
                    </article>
                    <article class="answer">
                        $25.00
                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            Is there a limit on how many people can join?
                        </div>
                    </article>
                    <article class="answer">
                        Yes.
                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            What is the ePHI token and where can it be used?
                        </div>
                    </article>
                    <article class="answer">
                        The ePHI token is a currency exclusively rewarded to UNIFIN members. It functions more as a stable coin backed by the UNIFIN Trust assets. The digial value increases over time and can be redeemed for UNIFIN's products and services at cost and with partnering businesses and organizations.
                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            Why isn't ePHI tokens sold on exchanges?
                        </div>
                    </article>
                    <article class="answer">
                        There is no benefit to list ePHI tokens on exchanges which are pron to manipulation, and used as a means to increase the wealth of a project's founders and developers. UNIFIN believes and building generational wealth, and the increasing value of ePHI will be based upon backed assets not mere speculation, and quick buck schemes.
                    </article>
                </section>
                <section class="askedQuestion">
                    <article class="question">
                        <div class="list"></div>
                        <div class="text">
                            What happens if I don't get 5 members within 30 days of my memebership?
                        </div>
                    </article>
                    <article class="answer">
                        UNIFIN does not spen resources on marketing or crypto giveaways to booster participation. All the value gained is reservered to increase member benefits. As a result, we rely solely on our members to spread the word about the benefits of membership by airdropping 75 ePHI tokens for each referral who becomes a member. <br>
                        <br>
                        But no need to worry! If by chance you do not recruit at least 5 members, UNIFIN will automactically assign members (FIFO, as members pools are available) after your 30 days to unlock your full benefits; however, you will miss out on the opporunity to receive ePHI rewards for those members you do not refer.

                    </article>
                </section>


            </div>

    </main>
    <?php include "includes/footer.php" ?>

    <script src="./js/default.js" type="module" defer></script>
</body>

</html>