<?php
session_start(); // Start the session
include_once 'db_con.php';

// Check if the user is logged in, if not, redirect to the login page or perform other necessary actions
if (!isset($_SESSION['userid']) || !isset($_SESSION['user_status'])) {
    // You can add a redirection here or handle it as needed
    // For example, you might want to redirect to the login page
    header('Location: login.php');
    exit(); // Terminate script execution
}
include_once 'db_con.php'
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Index</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="static/css/styles.css">
  <link rel="stylesheet" href="css/index.css">
  <style>
    h1{
      color:white;
    }
 
  </style>
</head>

<body>
  <header>
    <a class="logo" href="/">Franchise System</a>
    <nav>
      <ul class="nav__links">
        <li><a href="contact.php">Contact</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="result.php">Result</a></li>
      </ul>
    </nav>
    <div class="links">
      <a class="login-link"  href="logout.php">Logout</a>
    </div>
  </header>
  <div class="container">
    

    <div class="election_items">
      <?php
      $sql = "SELECT * FROM election ";
      $qry = mysqli_query($conn, $sql);
      if (mysqli_num_rows($qry) > 0) {
        while ($data = mysqli_fetch_assoc($qry)) {
          
          echo
            '<div class="election_item">
              <a href="showcandidate.php?id=' . $data['id'] . '">
                <div class="card">
                  <div class="card-item">
                    <img src="electionpics/' . $data['image'] . '">
                    <div class="card-title-bio">
                      <p id="title" class="font">' . $data['election_title'] . '</p>
                      <p id="bio" class="font">' . $data['bio'] . '</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>';
        }
      }
      ?>
    </div>
  </div>
</body>

</html>