<?php
include_once('db_con.php'); // Include database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $qry = mysqli_query($conn, "SELECT * FROM candidate WHERE id = $id");
    if ($qry) {
        $data = mysqli_fetch_assoc($qry);
    } else {
        echo "Error: " . mysqli_error($conn); // Print error message if query fails
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['name']?></title>
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

        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .image-container {
            flex-basis: 40%;
        }

        .image {
            max-width: 100%;
            border-radius: 5px;
        }

        .info-container {
            flex-basis: 55%;
        }

        .info {
            margin-top: 10px;
            padding-left: 20px;
            border-left: 1px solid #ccc;
        }

        .info strong {
            font-weight: bold;
        }

        .comment-box {
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1000px;
            padding: 20px;
            border-radius: 5px;
        }

        .comment-box h2 {
            margin-bottom: 10px;
        }

        .comment {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
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
            margin: 0 10px; /* Add margin for spacing */
        }

        .navbar a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="userdashboard.php">Home</a>
        <a href="javascript:history.go(-1);">Back</a>
    </div>
<?php if (isset($data)): ?>
    <h1>Candidate Detail</h1>
    <div class="container">
        <div class="image-container">
        <img class="image" src="electionpics/Candidate/<?php echo str_replace(' ', '_', $data['img']); ?>" alt="Candidate Image">
        </div>
        <div class="info-container">
            <div class="info">
                <p><strong>Name:</strong> <?php echo $data['name']; ?></p>
                <p><strong>Father Name:</strong> <?php echo $data['father']; ?></p>
                <p><strong>Mother Name:</strong> <?php echo $data['mother']; ?></p>
                <p><strong>Temporary Address:</strong> <?php echo $data['paddress']; ?></p>
                <p><strong>Permanent Address:</strong> <?php echo $data['taddress']; ?></p>
                <p><strong>DOB:</strong> <?php echo $data['dob']; ?></p>
                <p><strong>vote_reason:</strong> <?php echo $data['vote_reason']; ?></p>
            </div>
        </div>
        <div><a href="show"
    </div>
        <!-- Add more candidate details here as needed -->
<?php else: ?>
    <p>No candidate data found.</p>
<?php endif; ?>
</body>
</html>