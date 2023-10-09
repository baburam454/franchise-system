<?php

//include config.php
include('config.php');
include ('db_con.php');
function isEmailExists($connection, $email)
{
    $query = "SELECT COUNT(*) FROM registration WHERE email = '$email'";
    $result = mysqli_query($connection, $query);
    $count = mysqli_fetch_row($result)[0];
    return $count > 0;
}

function sendOTP($connection, $email, $otp)
{
    $query = "UPDATE registration SET sOTP = '$otp' WHERE email = '$email'";
    mysqli_query($connection, $query);
    mail($email, "Keep your OTP secret.", $otp);
}

function isOTPValid($connection, $email, $otp)
{
    $query = "SELECT COUNT(*) FROM users WHERE email = '$email' AND sOTP = '$otp'";
    $result = mysqli_query($connection, $query);
    $count = mysqli_fetch_row($result)[0];
    return $count > 0;
}

function changePassword($connection, $email, $newPassword)
{
    $query = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
    mysqli_query($connection, $query);
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    if (isEmailExists($conn, $email)) {
        $otp = uniqid(); //this generates random characters

        sendOTP($conn, $email, $otp);

        echo "OTP sent, enter below:<br>";
        echo "<form method='post' action=''>
                <input type='hidden' name='email' value='$email'>
                <input type='text' name='otp' placeholder='Enter OTP' required>
                <input type='submit' name='submitOTP' value='Submit OTP'>
              </form>";
    } else {
        echo "Invalid email.";
    }
} elseif (isset($_POST['submitOTP'])) {
    // Check if OTP is valid
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    if (isOTPValid($conn, $email, $otp)) {
        // OTP is valid, present new password fields
        echo "Enter new password:<br>";
        echo "<form method='post' action=''>
                <input type='hidden' name='email' value='$email'>
                <input type='password' name='newPassword' placeholder='New Password' required>
                <input type='password' name='confirmPassword' placeholder='Confirm Password' required>
                <input type='submit' name='submitPassword' value='Change Password'>
              </form>";
    } else {
        echo "Invalid OTP. Go back to re-enter.";
    }
} elseif (isset($_POST['submitPassword'])) {
    // Change the password in the database
    $email = $_POST['email'];
    $newPassword = md5($_POST['newPassword']);
    $confirmPassword = md5($_POST['confirmPassword']);

    if ($newPassword === $confirmPassword) {
        changePassword($conn, $email, $newPassword);
        echo "Password changed successfully. Login <a href='login.php'>here</a>.";
        exit();
    } else {
        echo "Passwords do not match. Go back to re-enter.";
    }
}
?>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            background-color: #f0f2f5 ;
            text-align: center;
            font-family: 'Roboto', sans-serif;
        }

        input[type=submit] {
            background-color: teal;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: rgba(0, 128, 128, 0.815);
        }

        input[type=email] {
            width: 30%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 2px solid #ccc;
        }

    </style>
</head>
<form method="post" action="">
    <input type="email" name="email" placeholder="Enter Email To Receive OTP" required>
    <input type="submit" name="submit" value="Submit">
</form>