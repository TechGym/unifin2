<?php
include_once "./functions/votes.func.php";
include_once "./functions/referrals.func.php";
?>
<nav id="controls">
    <a href="./votes" target="_blank" class="voting">
        <div class="image">
            <div class="voteCount">
                <?php echo count(createUnModeratedVotes()); ?>
            </div>
            <img src="./images/Voting.png" alt="Votes">
        </div>
        <p>Voting</p>
    </a>
    <?php $link = "http://twitter.com/intent/tweet?text=Here%20is%20my%20referral%20link%20to%20become%20a%20member%20of%20UNIFIN%20Trust%20DAO.%20Our%20democratic%20governance%20system%20allows%20each%20member%20to%20have%20an%20impact%20over%20the%20strategic%20direction%20of%20the%20DAO.%20Earn%20Rewards%20just%20for%20joining.&url=https%3A%2F%2Funifin.cc%2Fbackdoor%3Fref=" . fetchReferralCode();
    ?>
    <a href="<?php echo $link ?>" target="_blank" class="twitter">
        <div class="image">
            <img src="./images/Twitter.png" alt="Twitter">
        </div>
        <p>Twitter</p>
    </a>
    <a href="./map" target="_blank" class="members">
        <div class="image">
            <img src="./images/Members.png" alt="Members ">
        </div>
        <p>Members</p>
    </a>
    <a href="./campaign" target="_blank" class="sprint">
        <div class="image">
            <img src="./images/Sprint.png" alt="Sprint ">
        </div>
        <p>Sprint
            <?php
            echo $currentSprint;
            ?>
        </p>
    </a>
</nav>