<?php
$page_title = "Register";
include 'header.php'; // Include header file

// Start session and database connection
session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "BookReserve_DB";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);
    $firstName = $conn->real_escape_string($_POST['firstname']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $telephone = $conn->real_escape_string($_POST['telephone']);
    $mobile = $conn->real_escape_string($_POST['mobile']);

    // Mobile (numeric, 10 characters)
    if (!preg_match("/^\d{10}$/", $mobile)) {
        $error_message = "Mobile phone number should be numeric and 10 characters in length.";
    }
    // Password (at least 6 characters)
    elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    }
    // Password COnfirm
    elseif ($password !== $confirm_password) {
        $error_message = "Password and password confirmation do not match.";
    }
    // Check if username exists
    else {
        $check_sql = "SELECT * FROM Users WHERE Username = '$username'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows > 0) {
            $error_message = "Username already exists. Please choose another.";
        } else {
            // Insert new user
            $insert_sql = "INSERT INTO Users (Username, Password, FirstName, Surname, AddressLine, City, Telephone, Mobile)
                           VALUES ('$username', '$password', '$firstName', '$surname', '$address', '$city', '$telephone', '$mobile')";
            
            if ($conn->query($insert_sql) === TRUE) {
                $success_message = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error_message = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <style>
        body {
            background: url('images/realib.jpeg') no-repeat center center fixed;
            background-size: cover;
            background-color: #f8f9fa; 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #153259; /* Deep blue */
            margin-bottom: 20px;
            font-size: 26px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            color: #153259;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #153259;
            color: #ffffff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #1251a0;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a unique username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter a secure password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" id="surname" name="surname" placeholder="Enter your surname" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Enter your address" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" placeholder="Enter your city" required>
            </div>
            <div class="form-group">
                <label for="telephone">Telephone</label>
                <input type="text" id="telephone" name="telephone" placeholder="Enter your telephone number" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
    </div>

    <?php include 'footer.php'; 
     ?>
</body>
</html>
