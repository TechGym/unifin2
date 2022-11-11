<?php
include "includes/functions.php";
include "functions/proposals.func.php";
// Redirect to homepage when not a member 
statusRedirect();
$usersCouncil = checkUsersCouncil($loggedInfo['email']);
if ($usersCouncil != "5" && $usersCouncil != "50") {
    header("Location: ./home");
}

function checkPostValue($name) {
    if (isset($_POST[$name])) {
        return  $_POST[$name];
    } else {
        return "";
    }
}

function checkProposalValue($proposal, $name) {
    if (isset($proposal[$name])) {
        echo $proposal[$name];
    }
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
    <title>UNIFIN - Proposals</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/png">
    <link rel="stylesheet" href="./css/includes.css">
    <link rel="stylesheet" href="./css/proposals.css">
    <script src="https://kit.fontawesome.com/f6e3b67683.js" crossorigin="anonymous"></script>
</head>

<body class="dark">

    <main>

        <?php
        /*
        // DESCRIPTION OF SYNTAX USED
        1. If $_GET['type'] is not set, 
            display the proposal page(when $_GET['id']) is not set
                I.  if there are more than zero proposals at the time , display the proposals 
                II. else display a message telling them there are no pending proposals 
                    Give moderator the chance to view THEIR own proposals that have moderation finalized
                III. User can also switch between active and pending proposals
            else display a proposal based on the proposals id(when $_GET['id']) is  set
                I. if proposal has been moderated by moderator and hasn't been finalized
                    Display a message telling them they have already moderated the proposal and should check back later for results
                II. if proposal desn't exist, display an error message
                III . If proposal has been finalized display the results from the "proposal moderation"
                IV . If user is a moderator but did not moderate the proposal and proposal has been finalized display the result from the moderation
        2. if $_GET['type'] is set and is equal to 'new' display the create new proposal forms 
            If $_GET['id'] is set redirect to proposals?type=new
        3. if $_GET['type'] is set and is equal to 'edit'  display the edit proposal forms 
            If $_GET['id'] is not set redirect to proposals or if the proposal is not disapproved or if proposal does not exist
            Fetch the proposal with $_GET['id'] and fill in the value of various textareas
            */

        ?>
        <?php if (!isset($_GET["type"])) { ?>
            <?php if (!isset($_GET['id'])) { ?>
                <?php
                include "includes/notifications.php";
                ?>
                <section class="includes">
                    <?php
                    include "includes/header.php";
                    include "includes/menu.php";
                    ?>
                </section>
                <section id="container">
                    <section class="top">
                        <h3>Proposals</h3>
                        <?php if ($usersCouncil == "5") { ?>
                            <a class="createNew" href="./proposals?type=new">
                                Create New Proposal
                            </a>
                        <?php } ?>
                    </section>
                    <section class="switch">
                        <div class="switch_elem active">Pending</div>
                        <div class="switch_elem">Moderated</div>
                    </section>
                    <section id="proposals">
                        <article class="pendingProposals tab">
                            <?php
                            // Count active proposals (Ones the current user hasn't moderated)
                            $proposals = fetchActiveProposals();
                            $activeProposals = [];
                            foreach ($proposals as $proposal) {
                                $moderator = checkModerator($proposal['proposal_id']);
                                if ($moderator == "false") {
                                    array_push($activeProposals, $proposal);
                                }
                            }
                            ?>
                            <?php if (count($activeProposals) > 0) { ?>
                                <!-- The proposals section  -->
                                <?php

                                foreach ($activeProposals as $activeProposal) {
                                ?>
                                    <article class="proposal">
                                        <div class="proposal_heading">
                                            <div class="proposal_id">Id : <span>
                                                    #<?php
                                                        // Making id 6 figures 
                                                        $id = $activeProposal['proposal_id'];
                                                        echo padId($id);
                                                        ?>

                                                </span> </div>
                                            <div class="tag">Tag</div>
                                        </div>
                                        <div class="proposal_description">
                                            <h3 class="heading">
                                                <?php echo $activeProposal['heading']; ?>
                                            </h3>
                                            <p class="brief">
                                                <?php
                                                // Brief
                                                $description = $activeProposal['description'];
                                                $brief = truncateText($description, 200);
                                                echo  $brief;
                                                ?>
                                            </p>
                                            <a href="./proposals?id=<?php echo $id ?>">View Proposal</a>
                                        </div>

                                    </article>
                                <?php } ?>
                            <?php } else {   ?>
                                <div class="noProposals">
                                    <p>There are currently no pending proposals.</p>

                                </div>

                            <?php } ?>
                        </article>
                        <article class="moderatedProposals tab">
                            <?php
                            // Count moderated proposals (Ones the current user has moderated and hasn't been finalized)
                            $proposals = fetchActiveProposals();
                            $moderatedPendingProposals = [];
                            foreach ($proposals as $proposal) {
                                $moderator = checkModerator($proposal['proposal_id']);
                                if ($moderator == "true") {
                                    array_push($moderatedPendingProposals, $proposal);
                                }
                            }
                            $mProposals  = fetchModeratedProposals(0);
                            $proposals = [...$moderatedPendingProposals, ...$mProposals];
                            // Adding pending proposals that have been moderated 
                            if (count($proposals) > 0) {
                            ?>
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
                                                    <div class="proposal_desc"><?php echo truncateText($proposal['description'], 100) ?></div>
                                                    <a href="./proposals?id=<?php echo $proposal['proposal_id'] ?>" class="view_proposal">View Proposal</a>
                                                </div>

                                            </div>

                                        <?php } ?>
                                    </div>
                                    <?php if (count($mProposals) == 10) { ?>
                                        <div class="seeMore">
                                            See More
                                        </div>
                                    <?php }  ?>
                                </div>
                            <?php } else { ?>
                                <p class="noModerated">
                                    There are no moderated proposals
                                </p>
                            <?php } ?>
                        </article>

                    </section>
                <?php } ?>
                <?php if (isset($_GET['id'])) {
                    //  Fetching proposal
                    $proposal = fetchProposalWithId($_GET['id']);
                    // Check if proposal exists
                    if ($proposal) {

                        //  Check  if proposal has been finalized 
                        $proposalStatus = determineStatus($proposal['status'], $proposal["proposal_id"]);

                        // If proposal is still pending and has been moderated by current user then display already moderated 
                        $moderator = checkModerator($_GET["id"]);


                        $author = fetchAuthorWithId($proposal['author_id']);
                ?>
                        <section id="container">
                            <section id="aProposal">
                                <h3>Proposal Id: <span>#<?php echo $_GET['id'] ?></span></h3>
                                <h3 class='<?php echo $proposalStatus ?>'>Status: <?php echo $proposalStatus ?> </h3>

                                <section id="proposalDetails">
                                    <h3 class="heading">
                                        <?php echo $proposal['heading'] ?>
                                    </h3>
                                    <p class="description"> <?php echo $proposal['description'] ?></p>

                                    <div class="details">
                                        <?php if (!empty($proposal['estimate'])) { ?>
                                            <p class="estimate"> Estimated cost:<span> $<?php echo $proposal['estimate'] ?></span></p>
                                        <?php } ?>
                                        <?php if (!empty($proposal['estimateAnnual'])) { ?>
                                            <p class="estimate"> Estimated Annual Return:<span> $<?php echo $proposal['estimateAnnual'] ?></span></p>
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
                                        <?php
                                        /*
                                    1.Displaying rejection Reasons
                                    */
                                        ?>
                                        <?php if ($proposalStatus == "disapproved") { ?>
                                            <?php $rejectionCodes = fetchProposalRejectionCodes($_GET['id']) ?>
                                            <article class="reasonsForRejection">
                                                <h3>Rejection reasons</h3>
                                                <?php foreach ($rejectionCodes as $rejectionCode) { ?>

                                                    <div class="reasons">
                                                        <div class="tick"></div>
                                                        <div class="reason"><?php echo fetchRejectionWithCode($rejectionCode) ?></div>
                                                    </div>
                                                <?php  }  ?>
                                            </article>

                                        <?php  }  ?>

                                        <div class="author">
                                            <img src="./images/buy.png" alt="author_logo">
                                            <p class="name"><?php echo fetchFullName($author['email']) ?></p>
                                        </div>
                                        <p class="date">
                                            <?php echo formatDate(explode(" ", $proposal['timestamp'])[0])  ?>
                                        </p>

                                    </div>

                                </section>
                                <?php if ($proposal["author_id"] != $loggedInfo['user_id']) {
                                    if ($proposalStatus == "pending") {
                                        if ($moderator != "true") { ?>

                                            <section id="proposalControls">
                                                <button id="approve">Approve</button>
                                                <button id="reject">Reject</button>
                                            </section>
                                            <div class="rejectReason">
                                                <label for="rejectionReason">Rejection reason :</label>
                                                <select name="reject" id="rejectionReason">
                                                    <option width="100" value="" checked></option>
                                                    <!-- Fetch and display from js -->
                                                    <?php $reasons = fetchRejectionCodes();
                                                    foreach ($reasons as $reason) { ?>
                                                        <option width="100" value="<?php echo $reason['rejection_id'] ?>"><?php echo $reason['rejection']  ?></option>
                                                    <?php } ?>
                                                </select>
                                                <p class="err"></p>
                                                <button id="confirmReject">Confirm</button>
                                                <button id="cancelReject">Cancel</button>

                                            </div>
                                        <?php } else { ?>
                                            <p class="moderated">You have already moderated this proposal</p>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($proposal["author_id"] == $loggedInfo['user_id'] && ($proposalStatus == "disapproved" || $proposalStatus == "rejected")) { ?>
                                    <div class="editProposal">
                                        <a href="./proposals?type=edit&id=<?php echo $_GET['id'] ?>">Edit proposal</a>
                                    </div>
                                <?php } ?>
                            </section>
                        </section>


                    <?php } else { ?>
                        <div class='error'>
                            The proposal with id, <span><?php echo +$_GET['id'] ?></span> does not exist
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } elseif ($_GET['type'] == "new") { ?>
                <?php
                if (isset($_GET['id'])) { ?>
                    <script>
                        window.location.href = "./proposals?type=new";
                    </script>
                <?php }
                // Creating a new proposal
                ?>
                <section id="container2">
                    <form action="" id="newProposal">
                        <h3>Create New Proposal</h3>
                        <label for="heading"> Proposal Heading:</label>
                        <textarea name="heading" id="heading" aria-label="Heading"><?php echo checkPostValue("heading")  ?></textarea>

                        <label for="message"> Proposal Description:</label>
                        <textarea name="message" id="message" aria-label="Message"><?php echo checkPostValue("message") ?></textarea>

                        <label for="estimate"> Proposal Estimate <span>(in dollars, w/ no commas or $ sign):</span>:</label>
                        <textarea name="estimate" id="estimate" aria-label="Estimate" placeholder="Leave estimate empty if no money is involved"><?php echo checkPostValue("estimate")  ?></textarea>

                        <label for="estimate"> Proposal Estimate <span>(in dollars, w/ no commas or $ sign):</span>:</label>
                        <textarea name="estimateAnnual" id="estimateAnnal" aria-label="Annual Estimate" placeholder="Leave estimate empty if no money is involved"><?php echo checkPostValue("estimateAnnal")  ?></textarea>

                        <label for="tags"> Proposal Tags <span>(comma separated)</span>:</label>
                        <textarea name="tags" id="tags" aria-label="Tags"><?php echo checkPostValue("tags")   ?></textarea>

                        <p class="err"> </p>
                        <div class="controls">
                            <button class="submit" value="send" name="submit"> Submit </button>
                            <button name="cancel" class="cancel" value="cancel">Cancel </button>
                        </div>

                    </form>
                </section>

            <?php } elseif ($_GET["type"] == 'edit') { ?>
                <?php $proposal = fetchProposalWithId($_GET['id']); ?>
                <?php if (!isset($_GET['id']) || !$proposal || ($proposal['status'] != 'disapproved' && $proposal['status'] != "rejected")) { ?>
                    <script>
                        // Error messages instead will be done when adding touches 
                        window.location.href = "./proposals";
                    </script>
                <?php } ?>

                <section id="container2">
                    <form action="" id="editProposal">
                        <h3>Edit Proposal</h3>
                        <label for="heading"> Proposal Heading:</label>
                        <textarea name="heading" id="heading" aria-label="Heading"><?php echo preserveLineBreaksInEdit($proposal['heading']) ?></textarea>

                        <label for="message"> Proposal Description:</label>
                        <textarea name="message" id="message" aria-label="Message"><?php echo preserveLineBreaksInEdit($proposal['description']); ?></textarea>

                        <label for="estimate"> Proposal Estimate <span>(in dollars : to 2d.p)</span>:</label>
                        <textarea name="estimate" id="estimate" aria-label="Estimate" placeholder="Leave estimate empty if no money is involved"><?php echo checkProposalValue($proposal, "estimate") ?></textarea>

                        <label for="estimateAnnual"> Annual Return Estimate <span>(in dollars :to 2d.p)</span>:</label>
                        <textarea name="estimateAnnual" id="estimateAnnual" aria-label="Annual Return Estimate" placeholder="Leave estimate empty if no money is involved"><?php echo checkProposalValue($proposal, "estimateAnnual") ?></textarea>

                        <label for="tags"> Proposal Tags <span>(comma separated)</span>:</label>
                        <textarea name="tags" id="tags" aria-label="Tags"><?php echo $proposal['tags'];   ?></textarea>

                        <p class="err"> </p>
                        <div class="controls">
                            <button class="submit" value="send" name="submit"> Submit </button>
                            <button name="cancel" class="cancel" value="cancel">Cancel </button>
                        </div>

                    </form>
                </section>
            <?php } else { ?>
                <script>
                    window.location.href = "./proposals"
                </script>
            <?php } ?>

                </section>


    </main>

    <script src="./js/proposals.js" type="module" defer>

    </script>
</body>

</html>