<?php
include("db_con.php");

$firstNameErr = $lastNameErr = $addressErr = $dobErr = $genderErr = $citizenshipErr = $phoneErr = $emailErr = $passwordErr = $cpasswordErr = $countryErr = "";
$firstName = $lastName = $address = $dob = $gender = $citizenship_number = $phone = $email = $password = $cpassword = $country = $terms = "";
$formSubmitted = false; // Flag to check if the form has been submitted

if (isset($_POST['submit'])) {
    $firstName = test_input($_POST['first_name']);
    $lastName = test_input($_POST['last_name']);
    $address = test_input($_POST['address']);
    $dob = test_input($_POST['dob']);
    $gender = test_input($_POST['gender']);
    $citizenship_number = test_input($_POST['Citizenship_number']);
    $phone = test_input($_POST['phone_number']);
    $email = test_input($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $cpassword = test_input($_POST['cpassword']);
    $country = test_input($_POST['country']);

    // Validation code for each field
    if (empty($firstName)) {
        $firstNameErr = "First name is required";
    }
    if (empty($lastName)) {
        $lastNameErr = "Last name is required";
    }
    if (empty($address)) {
        $addressErr = "Address is required";
    }
    if (empty($dob)) {
        $dobErr = "Date of birth is required";
    }
    if (empty($gender)) {
        $genderErr = "Gender is required";
    }
    if (empty($citizenship_number)) {
        $citizenshipErr = "Citizenship number is required";
    }
    if (empty($phone)) {
        $phoneErr = "Phone number is required";
    }
    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }
    if (empty($password)) {
        $passwordErr = "Password is required";
    }
    if (empty($cpassword)) {
        $cpasswordErr = "Please confirm your password";
    } elseif ($password !== $cpassword) {
        $cpasswordErr = "Passwords do not match";
    }
    if (empty($country)) {
        $countryErr = "Country is required";
    }

    if (empty($firstNameErr) && empty($lastNameErr) && empty($addressErr) && empty($dobErr) && empty($genderErr) && empty($citizenshipErr) && empty($phoneErr) && empty($emailErr) && empty($passwordErr) && empty($countryErr)) {
        // Check if the citizenship number already exists
        $checkCitizenshipQuery = "SELECT id FROM registration WHERE citizenship = ?";
        $checkCitizenshipStmt = $conn->prepare($checkCitizenshipQuery);
        $checkCitizenshipStmt->bind_param("s", $citizenship_number);
        $checkCitizenshipStmt->execute();
        $checkCitizenshipResult = $checkCitizenshipStmt->get_result();

        // Check if the email address already exists
        $checkEmailQuery = "SELECT id FROM registration WHERE email = ?";
        $checkEmailStmt = $conn->prepare($checkEmailQuery);
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailResult = $checkEmailStmt->get_result();

        if ($checkCitizenshipResult->num_rows > 0) {
            // Citizenship number already exists
            $citizenshipErr = "Citizenship number is already registered.";
        } elseif ($checkEmailResult->num_rows > 0) {
            // Email address already exists
            $emailErr = "Email address is already registered.";
        } else {
            // Both citizenship number and email are unique, proceed with registration
            // Use prepared statements to insert data securely
            $stmt = $conn->prepare("INSERT INTO registration (first_name, last_name, address, dob, gender, phone, citizenship, email, password, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $firstName, $lastName, $address, $dob, $gender, $phone, $citizenship_number, $email, $password, $country);

            if ($stmt->execute()) {
                header('Location: login.php');
                $formSubmitted = true; // Set the flag to indicate successful submission
                // You can redirect or display a success message here
            } else {
                echo "Error: " . $stmt->error; // Handle database insertion error
            }

            $stmt->close();
        }
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f2f2f2;
            margin: 800;
            padding: 0;
        }

        .login-register-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        form {
            text-align: left;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-bottom: 1px solid #ccc;
            background-color: transparent;
            outline: none;
        }

        .error {
            color: red;
            font-size: 14px;
            font-weight: bold;
            text-align: left;
            margin-top: 5px;
        }

        input[type="submit"],
        input[type="button"] {
            background-color: blue;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #555;
        }

        .dob-input-container {
            display: flex;
            align-items: center;
        }

        .calendar-input {
            flex: 2;
        }
    </style>
</head>
<body>
    <div class="login-register-container">
        <h1>SIGNUP</h1>
        <?php if ($formSubmitted) : ?>
            <div class="success-message">Successfully Signed Up!</div>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <input type="text" id="first_name" name="first_name" placeholder="First Name">
            <span class="error"><?php echo (!empty($firstNameErr)) ? $firstNameErr : ''; ?></span>

            <input type="text" id="last_name" name="last_name" placeholder="Last Name">
            <span class="error"><?php echo (!empty($lastNameErr)) ? $lastNameErr : ''; ?></span>

            <input type="text" id="address" name="address" placeholder="Address">
            <span class="error"><?php echo (!empty($addressErr)) ? $addressErr : ''; ?></span>

            <div class="dob-input-container">
                <label class="dob-label" for="dob">DOB:</label>
                <input type="date" id="dob" name="dob" class="calendar-input">
            </div>
            <span class="error"><?php echo (!empty($dobErr)) ? $dobErr : ''; ?></span>

            <select name="gender">
                <option value="">Select gender:</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <span class="error"><?php echo (!empty($genderErr)) ? $genderErr : ''; ?></span>

            <input type="number" id="phone_number" name="phone_number" placeholder="Phone Number:">
            <span class="error"><?php echo (!empty($phoneErr)) ? $phoneErr : ''; ?></span>

            <input type="number" id="Citizenship_number" name="Citizenship_number" placeholder="Citizenship number:">
            <span class="error"><?php echo (!empty($citizenshipErr)) ? $citizenshipErr : ''; ?></span>

            <select name="country" id="country">
                <option value="">Select country</option>
                <option value="USA">USA</option>
                <option value="Canada">Canada</option>
                <!-- Add more countries here -->
            </select>
            <span class="error"><?php echo (!empty($countryErr)) ? $countryErr : ''; ?></span>

            <input type="email" id="email" name="email" placeholder="Email:">
            <span class="error"><?php echo (!empty($emailErr)) ? $emailErr : ''; ?></span>

            <input type="password" id="password" name="password" placeholder="Password:">
            <span class="error"><?php echo (!empty($passwordErr)) ? $passwordErr : ''; ?></span>

            <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password">
            <span class="error"><?php echo (!empty($cpasswordErr)) ? $cpasswordErr : ''; ?></span>
            <input name="submit" type="submit" value="Sign Up">
            <input type="button" value="Cancel" onclick="location.href='login.php'">
        </form>
    </div>
</body>
</html>
