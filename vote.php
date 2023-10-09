<?php
session_start();
include 'db_con.php';

// Get the message from the URL parameter
$msg = isset($_GET['msg']) ? urldecode($_GET['msg']) : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #333;
            text-align: center;
            background-color: #ffc107;
            padding: 20px;
            margin-top: 0;
        }

        p {
            font-size: 20px;
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }

        a:hover {
            background-color: #0056b3;
        }
        </style>
</head>
<body>
    <h1><marquee behavior="scroll" direction="left">Thank you for your valuable vote! 🗳️ We hope to see you in another election soon! 🇺🇸🙌.</marquee></h1>
    <p><?php echo $msg; ?></p>
    <a href="userdashboard.php">Go back to Dashboard</a>
</body>
</html>