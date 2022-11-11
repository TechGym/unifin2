<?php
// Votes page uses the same syntax and design as proposals page
//  All votes desings are in herited from proposal page 
include "includes/functions.php";
include "functions/proposals.func.php";
include "functions/votes.func.php";
statusRedirect();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache , no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <title>UNIFI - Votes</title>
    <meta http-equiv="Expires" content="0" />
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/votes.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
</head>

<body class="dark">
    <?php include "includes/notifications.php" ?>
    <?php
    /* 1.  Page can only be viewed if isset $_GET["id"]
        2. Fetch vote's proposal
    */
    ?>

    <?php if (isset($_GET['id'])) { ?>
        <?php
        $proposal = fetchProposalWithVoteId($_GET['id']);
        if (!$proposal) { ?>
            <main>
                <section id="invalidVote">
                    <p>The vote with id , <span><?php echo $_GET['id'] ?></span> does not exist</p>
                </section>
                <script>
                    window.addEventListener('load', setTimeout(() => {
                        window.location.href = "./votes"
                    }), 2000)
                </script>
            </main>
        <?php } else {
            $proposalId = $proposal["proposal_id"];
            $voteStatus = fetchVoteWithId($_GET['id'])["status"];
            $authorEmail = fetchAuthorWithId($proposal['author_id'])['email'];
            $author = fetchFullName($authorEmail);
            $usersVoteStatus = checkUserVoteStatus($loggedInfo['user_id'], $_GET['id']);
        ?>
            <main>
                <div class="includes">
                    <?php
                    include "includes/header.php";
                    include "includes/menu.php";
                    ?>
                </div>
                <div id="container">
                    <section id="aProposal">
                        <h3>Vote Id: <span>#<?php echo $_GET['id'] ?></span></h3>
                        <h3 class='<?php echo $voteStatus ?>'>Status: <?php echo $voteStatus ?> </h3>
                        <section id="proposalDetails">
                            <h3 class="heading">
                                <?php echo $proposal['heading'] ?>
                            </h3>
                            <p><?php echo $proposal['description'] ?></p>

                            <div class="details">
                                <?php if (!empty($proposal['estimate'])) { ?>
                                    <p class="estimate"> Estimated cost:<span> $<?php echo $proposal['estimate'] ?></span></p>
                                <?php } ?>
                                <div class="tags">
                                    <h3>Tags:</h3>
                                    <div class="tagsContainer">
                                        <?php
                                        $tags = explode(",", $proposal["tags"]);
                                        foreach ($tags as $tag) {
                                        ?>
                                            <div><?php echo $tag ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="information">
                                    <div class="author">
                                        <img src="./images/buy.png" alt="author_logo">
                                        <p class="name"><?php echo $author ?></p>
                                    </div>
                                    <p class="date">
                                        <?php echo formatDate(explode(" ", $proposal['timestamp'])[0])  ?>
                                    </p>
                                </div>
                                <div class="voteResults">
                                    <h3>Rejected: <span><?php echo fetchVotesResults($_GET['id'])['no_count'] ?></span></h3>
                                    <h3>Approved <span><?php echo fetchVotesResults($_GET['id'])['yes_count'] ?></span></h3>
                                </div>

                                <?php if ($voteStatus == "active" && !$usersVoteStatus) { ?>
                                    <section id="voteControls">
                                        <button id="approve">Yes</button>
                                        <button id="reject">No</button>
                                    </section>
                                <?php } elseif ($voteStatus == "active") {  ?>
                                    <p class="alreadyVoted">You have already voted on this</p>
                                <?php } ?>
                                <?php if ($proposal["author_id"] == $loggedInfo['user_id'] && $voteStatus == "rejected") { ?>
                                    <div class="editProposal">
                                        <a href="./proposals?type=edit&id=<?php echo $proposal['proposal_id'] ?>">Edit proposal</a>
                                    </div>
                                <?php } ?>
                            </div>

                        </section>

                    </section>
                </div>

            </main>
        <?php }
    } else { ?>
        <main>
            <div class="includes">
                <?php include "includes/header.php" ?>
                <?php include "includes/menu.php" ?>
            </div>
            <div id="container">
                <section class="top vote_top">
                    <h3>Votes</h3>
                </section>
                <section class="switch">
                    <div class="switch_elem active">Active</div>
                    <div class="switch_elem">Finished</div>
                </section>
                <section id="proposals">
                    <article class="pendingProposals tab">
                        <?php
                        // Fetch active unmoderated votes 
                        $activeVotes = createUnModeratedVotes();
                        $votedOn = createModeratedVotes();
                        ?>
                        <?php if (count($activeVotes) > 0) { ?>
                            <?php
                            $activeVotes = [...$activeVotes, ...$votedOn];
                            foreach ($activeVotes as $activeVote) {
                                $current = strtotime(gmdate("Y-m-d H:i:s"));
                                $time = strtotime($activeVote['timestamp']) + (3600 * 72);
                            ?>
                                <!-- The proposals section  -->
                                <article class="proposal" data-mod="<?php echo $activeVote['mod'] ?>" data-time="<?php echo $time - $current ?>">
                                    <div class="proposal_heading">
                                        <div class="proposal_id">Id : <span>
                                                #<?php
                                                    // Making id 6 figures 
                                                    $id = $activeVote['vote_id'];
                                                    for ($i = strlen($id); $i < 6; $i++) {
                                                        $id = "0" . $id;
                                                    }
                                                    echo $id;
                                                    ?>

                                            </span> </div>

                                        <div class="time">TimeLeft : <span> <?php echo checkRemainingVoteTime($activeVote['timestamp']) ?> </span> </div>
                                    </div>
                                    <div class="proposal_description">
                                        <h3 class="heading">
                                            <?php echo $activeVote['heading']; ?>
                                        </h3>
                                        <p class="brief">
                                            <?php
                                            // Brief
                                            $description = $activeVote['description'];
                                            $brief = truncateText($description, 200);
                                            echo  $brief;
                                            ?>
                                        </p>
                                        <a href="./votes?id=<?php echo $id ?>">View Vote</a>
                                    </div>

                                </article>
                            <?php } ?>
                        <?php } else {   ?>
                            <div class="noProposals">
                                <p>There are currently no active votes.</p>
                            </div>

                        <?php } ?>
                    </article>
                    <article class="moderatedProposals tab">
                        <?php
                        // Count moderated proposals (Ones the current user has moderated and hasn't been finalized)
                        $finishedVotes = fetchFinishedVotes(0);
                        if (count($finishedVotes) > 0) { ?>
                            <div class="heading">
                                <h3>Status</h3>
                                <h3>Vote</h3>
                            </div>
                            <div class="content">
                                <div class="proposals_container">
                                    <?php


                                    // Adding pending proposals that have been moderated 
                                    foreach ($finishedVotes as $finishedVote) {
                                    ?>
                                        <div class="content_elem">
                                            <div class="status ">
                                                <div class="status_container <?php echo determineStatus($finishedVote['status'], $finishedVote['proposal_id']) ?>">
                                                    <i class="fa-solid "></i>
                                                </div>
                                            </div>
                                            <div class="proposal">
                                                <div class="proposal_heading"><?php echo truncateText($finishedVote['heading'], 30) ?></div>
                                                <div class="proposal_desc"><?php echo truncateText($finishedVote['description'], 100) ?></div>
                                                <a href="./votes?id=<?php echo $finishedVote['vote_id'] ?>" class="view_proposal">View Vote</a>
                                            </div>
                                        </div>

                                    <?php } ?>
                                </div>
                                <?php if (count($finishedVotes) == 10) { ?>
                                    <div class="moreVotes">
                                        See More
                                    </div>
                                <?php }  ?>
                            </div>
                        <?php } else { ?>
                            <p class="noModerated">There are no finished votes</p>
                        <?php } ?>

                    </article>

                </section>
            </div>
        </main>
    <?php } ?>


    <?php include "includes/footer.php" ?>
    <script src=" ./js/votes.js" type="module" defer> </script>
    <script src=" ./js/proposals.js" type="module" defer> </script>

</body>

</html>