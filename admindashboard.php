<?php
include 'db_con.php';

include_once('SESSION.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .info {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        .nav {
            background-color: #333;
            overflow: hidden;
        }

        .nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .table {
            margin: 20px;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .actions {
            display: flex;
            justify-content: space-around;
        }

        .edit-delete a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }

        .edit-delete a:hover {
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="info"><h3>This is the Admin Dashboard</h3></div>
    <div class="nav">
        <a href="addelection.php">Add Election</a>
        <a href="addcandidate.php">Add Candidates</a>
        <a href="managevoters.php">Manage Voters</a>
        <a href="result.php">Result</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="table">
        <table>
            <tr>
                <th>Sn</th>
                <th>Title</th>
                <th>Num Candidates</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </tr>
            <?php
                $sql = "SELECT * FROM election";
                $qry = mysqli_query($conn, $sql);

                if (mysqli_num_rows($qry) > 0) {
                    $count = 1; // Initialize counter variable
                    while ($data2 = mysqli_fetch_assoc($qry)) {
                        echo "<tr>";
                        echo "<td>" . $count . "</td>";
                        echo "<td><a href='addcandidate.php?id=" . $data2['id'] . "'>" . $data2['election_title'] . "</a></td>";
                        echo "<td>" . $data2['no_of_candidate'] . "</td>";
                        echo "<td>" . $data2['start_date'] . "</td>";
                        echo "<td>" . $data2['end_date'] . "</td>";
                        echo "<td class='actions'>";
                        echo "<div class='edit-delete'>";
                        echo "<a href='edit_election.php?id=" . $data2['id'] . "'>Edit</a>";
                        echo "</div>";
                        echo "<div class='edit-delete'>";
                        echo "<a href='delete_election.php?id=" . $data2['id'] . "' onclick=\"return confirm('Are you sure you want to delete this election?')\">Delete</a>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";

                        $count++; // Increment counter variable
                    }
                }
            ?>
        </table>
    </div>
</body>
</html>     
