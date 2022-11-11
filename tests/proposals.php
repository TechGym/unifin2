<?php
include "../includes/functions.php";
include "../functions/proposals.func.php";
include "../functions/notifications.func.php";
include "../functions/admin/general.func.php";


$tg = ['bitcoin', 'ethereum', 'estates', "cryptocurrencies", 'investments'];
$choices = ['approve', "disapprove"];

$councilof5Members = fetchCouncilOf5();
// Create new proposals 

$statuses = ['approved', "disapproved ", "pending"];
$councilMembers = strval(fetchAllCouncilMembers());
$councilMembers = explode(" ", $councilMembers);

$create = true;
if (isset($create)) {
    for ($i = 0; $i < 5; $i++) {
        $author_id = $councilMembers[rand(0, count($councilMembers) - 1)];
        $unique = uniqid();

        $_POST['heading'] = "This is  test " . $unique;
        $_POST['description'] = "    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
     Laudantium, quam veritatis magnam iure
     praesentium modi perspiciatis repellat voluptate doloremque corporis.
    " . $unique;

        $_POST['tags'] = $tg[rand(0, count($tg) - 1)];

        $_POST['estimate'] = "0.00";
        $_POST['create'] = "true";
        if (isset($_POST["create"])) {
            // 1.
            $heading = htmlspecialchars($_POST['heading']);
            $description = htmlspecialchars($_POST['description']);
            $tags = htmlspecialchars($_POST['tags']);
            $estimate = htmlspecialchars($_POST['estimate']);
            $estimate = floatval($estimate);
            $time = gmdate("Y-m-d H:i:s");
            // 2
            $description = mysqli_real_escape_string($dbConnect, $description);
            $description = preserveWhiteSpaces($description);


            $sql = "INSERT into proposals (author_id , estimate, heading , description , tags , timestamp ) VALUES ('$author_id'  , '$estimate' , '$heading' , '$description', '$tags' , '$time') ";
            $create = $dbConnect->query($sql);

            if ($create) {
                // Fetching the proposalId of the inserted proposal
                $proposalId = fetchAuthorsLatestProposal($author_id);

                // Inserting proposal into notifications to notify council members except author of a new proposal

                $notifications = insertNewProposal($proposalId);

                echo "success";
            }
        }
    }
}

// $moderate = true;
if (isset($moderate)) {
    //  Create random proposal moderations
    $moderators = strval(fetchAllCouncilMembers());
    $moderators = explode(" ", $moderators);
    $proposals = fetchActiveProposals();

    foreach ($proposals as $proposal) {
        foreach ($moderators as $moderator) {
            $choice = $choices[rand(0, count($choices) - 1)];
            $loggedInfo['user_id'] = $moderator;
            $_POST["proposalId"] = $proposal['proposal_id'];
            echo ($_POST['proposalId']);

            if ($choice == "disapprove") {
                $_POST['reject'] = true;

                $rejection_code = rand(1, 4);
                $_POST['code'] = $rejection_code;
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
            } else {
                $_POST['approve'] = true;
                if (isset($_POST["approve"])) {

                    $proposalId = +$_POST["proposalId"];
                    $userId = $loggedInfo["user_id"];

                    // 1
                    $sql = "SELECT approvers FROM proposals  WHERE proposal_id = '$proposalId' ";
                    $result = $dbConnect->query($sql);
                    $result = $result->fetch_assoc()['approvers'];

                    //         // 2. 
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
            }
            echo "<br>";
        }
        echo "<br>";
        echo "<br>";
    }
}
