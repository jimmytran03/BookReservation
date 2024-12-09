<?php
$page_title = "Dashboard";
include 'header.php'; // Include the header file

// Start a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

$username = $_SESSION['username']; // Get the logged-in user's username
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <style>
        body {
            background: url('Images/realib.jpeg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 50px); /* Adjust for footer */
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        .button-group a {
            display: inline-block;
            margin: 10px 5px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .button-group a:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <p>Explore the options below to manage your library activities:</p>
            <div class="button-group">
                <a href="search.php">Search Books</a>
                <a href="reserve.php">Reserve a Book</a>
                <a href="view_reserved.php">View My Reservations</a>
            </div>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Book Reservation System. All rights reserved.
    </footer>
</body>
</html>
