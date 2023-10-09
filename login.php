<?php
session_start();
include 'db_con.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `registration` WHERE `email` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            $user_status = $row['user_status'];
            $_SESSION['userid'] = $row['id'];
            $_SESSION['user_status'] = $user_status;

            session_start(); // Start the session if it's not already started

            if (isset($_SESSION['view_only'])) {
                unset($_SESSION['view_only']); // Remove the 'user_status' session variable
            }
            session_write_close();

        

            if (isset($_SESSION['userid'])) {
                // Redirect to appropriate dashboard
                if ($user_status == 0) {
                    header("Location: userdashboard.php");
                    exit();
                } else if ($user_status == 1) {
                    header("Location: admindashboard.php");
                    exit();
                }
            }
        } else {
            // Password is incorrect
            echo "Invalid password.";
        }
    } else {
        // User not found
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        /* CSS for login page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* Horizontally center */
            align-items: center; /* Vertically center */
            height: 100vh; /* 100% viewport height */
        }

        .login-register-container {
            background-color: #fff;
            max-width: 400px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
        }

        form {
            text-align: center;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-register-container">
        <h1>Login Page</h1>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="submit" type="submit">Login</button>
        </form>
        <p>Create an account <a href="signup.php">Here</a></p>
        <p><a href="forgot_password.php">Forgot Password?</a></p>
    </div>
</body>
</html>
