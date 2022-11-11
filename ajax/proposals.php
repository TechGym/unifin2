<?php
include "../includes/functions.php";
include "../functions/notifications.func.php";
include "../functions/proposals.func.php";
include "../functions/votes.func.php";
/*
1. Select approvers of the proposal
2. If approvers is NULL update without concatination else concat approvers, " " and current userId 

*/
if (isset($_POST["approve"])) {
    $proposalId = +$_POST["proposalId"];
    $userId = $loggedInfo["user_id"];

    // 1
    $sql = "SELECT approvers FROM proposals  WHERE proposal_id = '$proposalId' ";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_assoc()['approvers'];

    // 2. 
    $sql = "UPDATE proposals SET  approvers = CONCAT(approvers ,' ' , $userId)  WHERE proposal_id = '$proposalId' ";
    if (!$result) {
        $sql = "UPDATE proposals SET  approvers =$userId  WHERE proposal_id = '$proposalId' ";
    }
    // Updating state to disapproved 
    $result = $dbConnect->query($sql);
    if ($result) {
        echo "success";
    }
}

/*
1. Select rejectors of the proposal
2. If rejectors is NULL update without concatination else concat rejectors, " " and current userId 
*/
if (isset($_POST["reject"])) {
    $proposalId = +$_POST["proposalId"];
    $userId = $loggedInfo["user_id"];
    $code = $_POST['code'];

    // Fetching approvers 
    $sql = "SELECT rejectors FROM proposals  WHERE proposal_id = '$proposalId' ";
    $result = $dbConnect->query($sql);
    $result = $result->fetch_assoc()['rejectors'];

    $sql = "UPDATE proposals SET  rejectors = CONCAT(rejectors ,' ', $userId) , rejection_codes =  CONCAT(rejection_codes ,' ', $code)  WHERE proposal_id = '$proposalId' ";
    if (!$result) {
        $sql = "UPDATE proposals SET  rejectors =$userId , rejection_codes = $code  WHERE proposal_id = '$proposalId' ";
    }

    $result = $dbConnect->query($sql);

    if ($result) {
        echo "success";
    }
}

// NB: Note both edit a proposal and create new proposal use the  script below to insert data 
/*
1 . Sanitize data
2. Insert into database
*/
if (isset($_POST["create"])) {
    // 1.
    $heading = htmlspecialchars($_POST['heading']);
    $description = htmlspecialchars($_POST['description']);
    $tags = htmlspecialchars($_POST['tags']);
    $estimate = htmlspecialchars($_POST['estimate']);
    $estimate = floatval($estimate);
    $estimateAnnual = htmlspecialchars($_POST['estimateAnnual']);
    $estimateAnnual = floatval($estimateAnnual);
    $time = gmdate("Y-m-d H:i:s");
    $author_id = (int) $loggedInfo["user_id"];

    // 2
    $description = mysqli_real_escape_string($dbConnect, $description);
    $description = preserveWhiteSpaces($description);

    // Check moderator
    $isModerator = checkIfUserIsACouncilOf5Member();
    if ($isModerator) {
        $sql = "INSERT into proposals (author_id , estimate, heading , description , tags , timestamp , estimateAnnual ) VALUES ('$author_id'  , '$estimate' , '$heading' , '$description', '$tags' , '$time' , '$estimateAnnual') ";
        $create = $dbConnect->query($sql);

        // Fetching the proposalId of the inserted proposal
        $proposalId = fetchAuthorsLatestProposal($author_id);

        // Inserting proposal into notifications to notify council members except author of a new proposal
        $modertors = fetchAllCouncilMembers();
        $notifications = insertNewProposal($proposalId);

        echo "success";
    } else {
        echo "User is not part of the council of 5";
    }
}


// THE SEE MORE FUNCTIONALITY ON Profile Page
if (isset($_POST["viewMore"])) {
    $skip = $_POST['skip'];
    $id = $loggedInfo['user_id'];
    $sql = "SELECT * FROM proposals WHERE author_id = '$id' LIMIT $skip , 4";
    $proposals = $dbConnect->query($sql);
    $proposal = $proposals->fetch_all(MYSQLI_ASSOC);
    setInformationForJs($proposals);
}

if (isset($_POST["removeProposal"])) {
    $id = $_POST['proposal_id'];
    $sql = "UPDATE proposals SET show_proposal = 'false' WHERE proposal_id = '$id'";
    $proposals = $dbConnect->query($sql);

    echo 'proposalRemoved';
}

if (isset($_POST["viewMoreModeratedProposals"])) {
    $skip = $_POST['skip'];
    $proposals = fetchModeratedProposals($skip);
    setInformationForJs($proposals);
}
if (isset($_POST["viewMoreFinishedVotes"])) {
    $skip = $_POST['skip'];
    $proposals = fetchFinishedVotes($skip);
    setInformationForJs($proposals);
}
function setInformationForJs($proposals) {
    $output = [];
    foreach ($proposals as $proposal) {
        $status = determineStatus($proposal['status'], $proposal['proposal_id']);
        $heading = truncateText($proposal['heading'], 30);
        $proposal_desc = truncateText($proposal['description'], 120);
        $link = "./proposals?id=" . $proposal['proposal_id'];
        $type = "Proposal";
        $proposalId = $proposal['proposal_id'];
        if (isset($proposal['vote_id'])) {
            $link =  "./votes?id=" . $proposal['vote_id'];
            $type = "Vote";
        }
        $id = $proposal['proposal_id'];
        $proposalInfo = ["status" => $status, "heading" => $heading, "proposal_desc" => $proposal_desc, "link" => $link, "type" => $type, 'id' => $proposalId];
        array_push($output, $proposalInfo);
    }
    echo json_encode($output);
}
