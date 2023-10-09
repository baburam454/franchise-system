<?php
session_start(); 
include 'db_con.php';

// Check if the user is logged in and has admin access
if (!isset($_SESSION['user_status']) || $_SESSION['user_status'] != 1) {
    header("Location: login.php");
    exit();
}

$msg = ""; // error message

if (isset($_POST['submit'])) {
    // Retrieve form data
    $electiontitle = $_POST['election_title'];
    $no_of_candidate = $_POST['no_of_candidate'];
    $bio = $_POST['bio'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) { // Check if the file was uploaded successfully
        // Process and move the uploaded image
        $imgname = $_FILES['image']['name'];
        $imgTemp = $_FILES['image']['tmp_name'];
        $imagePath = "electionpics/" . $imgname;
        move_uploaded_file($imgTemp, $imagePath);

        if (empty($electiontitle) || empty($no_of_candidate) || empty($bio) || empty($startDate) || empty($endDate)) {
            $msg = "Please enter all details.";
        } else {
            $sql = "INSERT INTO election (election_title, no_of_candidate, bio, start_date, end_date, image) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $electiontitle, $no_of_candidate, $bio, $startDate, $endDate, $imgname);

            if ($stmt->execute()) {
                $electionid = mysqli_insert_id($conn);
                header("Location: addcandidate.php?electionid=$electionid");
                exit();
            } else {
                $msg = "Failed to add election.";
            }
        }
    } else {
        $msg = "Error in image upload.";
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Election</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 60%;
        }

        table {
            width: 100%;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #444;
        }

        .add-candidate-link {
            display: block;
            text-align: center;
            margin-top: 10px;
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
    </style>
</head>
<body>
    <h1>Add Election</h1>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <table>
            <tr>
        <td><label>Election Title:</label></td>
        <td><input type="text" name="election_title"  required></td>
            </tr>
            <tr>
        <td><label>No of candidate:</label></td>
        <td><input type="number" name="no_of_candidate"  required></td>
            </tr>
        <tr>
        <td><label> Bio </label></td>
        <td><input type="text" name= "bio" required></td>
        </tr>
        <tr>
        <td><label>Start Date:</label></td>
        <td><input type="date" id="start_date" name="start_date" required></td>
        </tr>
        <tr>
        <td><label>End Date:</label></td>
        <td><input type="date" id="end_date"name="end_date" required></td>
        </tr>
        <tr>
        <td><label>Img:</label></td>
        <td><input type="file" name = "image" accept ="image/*" required></td>
</tr>
        <tr>
        <td><button type="submit" name="submit" >Add election</button></td>
        </tr>

        <?php
            if(isset($electionid['id'])){
                echo '<a href="addcandidate.php?electionid='.$electionid.' ">Add Candidate</a>';
            }
        ?>
        </table>
    </form>
    <a href="admindashboard.php">Go back to Admin Dashboard</a>
    <?php echo $msg;?>

    <div class="table">
        <table>
        <tr>
            <th>Sn</th>
            <th>Title</th>
            <th>Num Candidates</th>
            <th>Start date</th>
            <th>End Date</th>
        </tr>

        <?php 
            $sql = "SELECT * FROM election";
            $qry = mysqli_query($conn, $sql);

            if(mysqli_num_rows($qry) > 0) {
                $count = 1; // Initialize counter variable
                while($data2 = mysqli_fetch_assoc($qry)) {
                    echo '<tr>
                    <td>' . $count . '</td>
                    <td><a href="addcandidate.php?id=' . $data2['id'] . '">' . $data2['election_title'] . '</a></td>
                    <td>' . $data2['no_of_candidate'] . '</td>
                    <td>' . $data2['start_date'] . '</td>
                    <td>' . $data2['end_date'] . '</td>
                    </tr>';

                    $count++; // Increment counter variable
                }
            }
        ?>
        </table>
    </div>
    <script>
    // Get references to the date input fields
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Function to disable past dates in the date input fields
    function disablePastDates() {
      const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
      startDateInput.setAttribute('min', today);
      endDateInput.setAttribute('min', today);
    }

    // Function to update the minimum date for the end date input based on the start date
    function updateEndDateMin() {
      const startDate = new Date(startDateInput.value);
      endDateInput.setAttribute('min', startDateInput.value);
      if (endDateInput.value && new Date(endDateInput.value) < startDate) {
        endDateInput.value = startDateInput.value;
      }
    }

    // Function to update the maximum date for the start date input based on the end date
    function updateStartDateMax() {
      const endDate = new Date(endDateInput.value);
      startDateInput.setAttribute('max', endDateInput.value);
      if (startDateInput.value && new Date(startDateInput.value) > endDate) {
        startDateInput.value = endDateInput.value;
      }
    }

    // Initialize the calendar and set the end date min attribute based on start date
    disablePastDates();
    updateEndDateMin();
    updateStartDateMax();

    // Listen for changes in the start date and end date and update attributes accordingly
    startDateInput.addEventListener('change', () => {
      updateEndDateMin();
      updateStartDateMax();
    });

    endDateInput.addEventListener('change', () => {
      updateEndDateMin();
      updateStartDateMax();
    });
  </script>

</body>
</html>