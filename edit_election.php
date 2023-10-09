<?php
include 'db_con.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve the election data from the database
    $selectSql = "SELECT * FROM election WHERE id = $id";
    $electionData = mysqli_query($conn, $selectSql);

    if (!$electionData) {
        echo "Error fetching election data: " . mysqli_error($conn);
        exit();
    }

    $election = mysqli_fetch_assoc($electionData);

    // Handle form submission
    if (isset($_POST['submit'])) {
        $newTitle = $_POST['election_title'];
        $newNumCandidates = $_POST['no_of_candidate'];
        $newStartDate = $_POST['start_date'];
        $newEndDate = $_POST['end_date'];

        $updateSql = "UPDATE election SET 
                      election_title = '$newTitle', 
                      no_of_candidate = '$newNumCandidates', 
                      start_date = '$newStartDate', 
                      end_date = '$newEndDate' 
                      WHERE id = $id";

        if (mysqli_query($conn, $updateSql)) {
            header("Location: admindashboard.php");
            exit();
        } else {
            echo "Error updating election: " . mysqli_error($conn);
        }
    }
} else {
    // No election ID provided, redirect to dashboard
    header("Location: admindashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Election</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        form {
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Edit Election</h1>
    
    <form method="POST" action="">
        <label for="election_title">Election Title:</label>
        <input type="text" name="election_title" value="<?php echo $election['election_title']; ?>"><br>
        
        <label for="no_of_candidate">Number of Candidates:</label>
        <input type="number" name="no_of_candidate" value="<?php echo $election['no_of_candidate']; ?>"><br>
        
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" value="<?php echo $election['start_date']; ?>"><br>
        
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" value="<?php echo $election['end_date']; ?>"><br>

        <label>Img:</label>
        <input type="file" name = "image" accept ="image/*" required>
        
        <button type="submit" name="submit">Update Election</button>
    </form>
    
    <a href="admindashboard.php">Go back to Admin Dashboard</a>
</body>
</html>
