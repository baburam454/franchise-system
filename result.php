<?php

session_start(); // Start the session

include 'db_con.php'; // Include your database connection
include 'session.php';

// Fetch elections from the database
$electionsQuery = "SELECT id, election_title FROM election";
$electionsResult = mysqli_query($conn, $electionsQuery);

// Initialize variables
$selectedElectionId = null;
$resultsResult = null; // Initialize the results variable

// Handle form submission
if (isset($_POST['getResult'])) {
    $selectedElectionId = $_POST['election_id'];

    // Fetch election results based on selected election ID
    $resultQuery = "SELECT * FROM candidate c
                    JOIN election e ON c.election_id = e.id
                    WHERE c.election_id = $selectedElectionId
                    ORDER BY vote DESC";

    $resultsResult = mysqli_query($conn, $resultQuery);

    // Check if the query was successful
    if (!$resultsResult) {
        die("Error in SQL query: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        select {
            padding: 5px;
        }

        button {
            padding: 5px 10px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Election Result</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="election">Select Election:</label>
        <select name="election_id" id="election">
            <?php while ($election = mysqli_fetch_assoc($electionsResult)): ?>
                <option value="<?php echo $election['id']; ?>" <?php if ($selectedElectionId == $election['id']) echo 'selected'; ?>>
                    <?php echo $election['election_title']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="getResult">Show Result</button>
    </form>
    <?php if ($resultsResult): ?>
        <table>
            <tr>
                <th>Candidate Name</th>
                <th>Total Votes</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($resultsResult)) {
                echo '<tr>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['vote'] . '</td>';
                echo '</tr>';
            } ?>
        </table>
    <?php endif; ?>

    <?php

    if (isset($_SESSION['user_status'])) {
        if ($_SESSION['user_status'] == 0) {
            ?>
        <a href="userdashboard.php">Go back to Dashboard</a>
        <?php
        } else if ($_SESSION['user_status'] == 1) {
            ?>
        <a href="admindashboard.php">Go back to Dashboard</a>
        <?php
        }
    } else {
     ?>
        <a href="index.php">Go back to Dashboard</a>
     <?php 
    }
    ?>
</body>
</html>
