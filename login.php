<?php
$page_title = "Login";
include 'header.php'; // Include header file

// Start a session and database connection
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $conn->real_escape_string($_POST['username']);
    $pass = $conn->real_escape_string($_POST['password']);

    // Query to check if username and password match
    $sql = "SELECT * FROM Users WHERE Username = '$user' AND Password = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $user;
        header("Location: dashboard.php"); // Redirect to the dashboard
        exit;
    } else {
        // Login failed
        $error_message = "Invalid username or password. <a href='register.php'>Click here to register</a>.";
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
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: url('images/realib.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #153259;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #153259;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background: #153259;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-group button:hover {
            background: #1251a0;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .info-text {
            font-size: 14px;
            color: #555;
            margin-top: 20px;
        }

        .info-text a {
            color: #007bff;
            text-decoration: none;
        }

        .info-text a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #153259;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }

        footer a {
            color: #85c1e9;
            text-decoration: none;
        }

        footer a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <h2>Login to Your Account</h2>
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Login</button>
                </div>
            </form>

            <div class="info-text">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Reservation System. All rights reserved.</p>
    </footer>
</body>
</html>
