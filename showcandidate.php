<?php
include_once('db_con.php');

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_status'])) {
    header("Location: userdashboard.php");
    exit();
} elseif ($_SESSION['user_status'] == 1) {
    header("Location: admindashboard.php");
    exit();
}

$eid = isset($_GET['id']) ? $_GET['id'] : 0;
$msg = ""; // Initialize the message variable with an empty string

if (isset($_POST['vote'])) {
    $vote = intval($_POST['vote']);
    
    if ($vote > 0) {
        // Check if the user has already voted for this election
        $uid = $_SESSION['userid'];
        $checkVoteQuery = "SELECT * FROM vote WHERE uid = '$uid' AND eid = $eid";
        $checkVoteResult = mysqli_query($conn, $checkVoteQuery);

        if (mysqli_num_rows($checkVoteResult) == 0) {
            // User hasn't voted yet for this election
            $candidateId = $vote;

            // Start a transaction for database updates
            mysqli_begin_transaction($conn);

            // Insert the vote record with the candidate ID
            $iqry = mysqli_query($conn, "INSERT INTO vote (uid, eid, candidate_id) VALUES ('$uid', $eid, $candidateId)");

            if ($iqry) {
                $uqry = mysqli_query($conn, "UPDATE candidate SET vote = vote + 1 WHERE id = $candidateId");

                if ($uqry) {
                    // Commit the transaction if both queries are successful
                    mysqli_commit($conn);
                    $msg = "Voting Successfully Completed";
                } else {
                    // Rollback the transaction if the update query fails
                    mysqli_rollback($conn);
                    $msg = "Couldn't update vote count";
                }
            } else {
                // Rollback the transaction if the insert query fails
                mysqli_rollback($conn);
                $msg = "Couldn't complete voting";
            }
        } else {
            $msg = "You have already voted for this election";
        }
    }
    
    // Redirect to vote.php with the message as a URL parameter
    header("Location: vote.php?msg=" . urlencode($msg));
    exit();
}

$sql = "SELECT * FROM candidate WHERE election_id = $eid";
$qry = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Candidate</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
            display: flex;
            justify-content: center; /* Center content horizontally */
            align-items: center; /* Center content vertically */
        }

        .navbar a {
            font-size: 16px;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #555;
        }

        .container {
            padding: 20px;
        }

        .election_items {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
        }

        .candidateVote {
            position: relative;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            width: calc(25% - 20px);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .candidateVote:hover {
            transform: scale(1.05);
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }

        .candidateVote img {
            max-width: 100%;
            border-radius: 80%;
            margin-bottom: 10px;
        }

        .candidate_name {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .vote_btn button {
            display: inline-block;
            padding: 5px 15px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .vote_btn button:hover {
            background-color: #555;
        }

        /* Notification Styles */
        .notification {
            display: none;
            background-color: #333;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            z-index: 1;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="userdashboard.php">Home</a>
    </div>
    <div class="container">
        <div class="election_items">
            <?php
            if (mysqli_num_rows($qry) > 0) {
                while ($data = mysqli_fetch_assoc($qry)) {
                    echo '<div class="candidateVote">
                    <a href="candidate_detail.php?id=' . $data['id'] . '">
                        <img src="electionpics/Candidate/' . $data['img'] . '" alt="Candidate Image">
                        <p class="candidate_name">' . $data['name'] . '</p>
                    </a>
                    <div class="vote_btn">
                        <form method="POST">
                            <input type="hidden" name="vote" value="' . $data['id'] . '">
                            <button type="submit">Vote</button>
                        </form>
                    </div>
                    </div>';
                }
            }
            ?>
            <p><?php echo $msg; ?></p>
        </div>
    </div>
    
    <script>
        var candidateId;
        var candidateName;

        function confirmVote(id, name) {
            candidateId = id;
            candidateName = name;
            var notification = document.getElementById("confirmationNotification" + id);
            notification.style.display = "block";
            document.getElementById("candidateName" + id).innerText = name;
        }

        function cancelVote(id) {
            var notification = document.getElementById("confirmationNotification" + id);
            notification.style.display = "none";
        }

        function castVote(id) {
            window.location.href = "vote.php?id=" + id;
        }
    </script>
</body>
</html>
