<?php
session_start();
include_once('db_con.php');

// Store user status in session
$_SESSION['view_only'] = 'view_only';

if (isset($_SESSION['user_status'])) {
  if ($_SESSION['user_status'] == 0) {  
    header("Location:userdashboard.php");
  } elseif ($_SESSION['user_status'] == 1) {
   header("Location:admindashboard.php");
    exit();
  }
}
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
      <a class="login-link" href="login.php">Login</a>
      <a class="register-link" href="signup.php">Sign Up</a>
    </div>

  </header>

<div class="container">  
    <h1><php? 
    
    ?></h1>
    <div class="election_items">
      <?php
      $sql = "SELECT * FROM election ";
      $qry = mysqli_query($conn, $sql);

      if (mysqli_num_rows($qry) > 0) {
        while ($data = mysqli_fetch_assoc($qry)) {

          echo '<div class="election_item">
          <div class="card">
            <a href="login.php">
              <div class="card-item">
                <img src="electionpics/' . $data['image'] . '">
                <div class="card-title-bio">
                  <p id="title" class="font">' . $data['election_title'] . '</p>
                  <p id="bio" class="font">' . $data['bio'] . '</p>
                </div>
              </div>
            </a>
          </div>
      </div>';

        }                                 
      }
      ?>
    </div>
  </div>
  </div>
</body>

</html>