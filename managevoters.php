<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection
include 'db_con.php';

// Query to retrieve voter information from user registration table
$query = "SELECT id, first_name, last_name, dob, gender, phone, citizenship, email, address FROM registration WHERE user_status = 0";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Voters</title>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Manage Voters</h1>
    
    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table border="1" id="mytable">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Phone Number</th>
                <th>Citizenship Number</th>
                <th>Email</th>
                <th>Address</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['dob']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['citizenship']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No voters found.</p>
    <?php endif; ?>
    
    <?php
    // Close the database connection
    mysqli_close($conn);
    ?>
    
    <a href="admindashboard.php">Go back to Admin Dashboard</a>
    <script>
        let table = new DataTable('#myTable');
    </script>
</body>
</html>