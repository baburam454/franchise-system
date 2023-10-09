<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['userid'])) {

    echo ($_SESSION['userid']);
    // Unset all session variables
    session_unset();

    // Destroy the session
    $destroy=session_destroy();

    // Redirect to the index page
  if($destroy){
    header('Location: index.php');}
    
} 
?>
