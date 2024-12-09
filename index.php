<?php
$page_title = "Home";
include 'header.php'; // Include header file

// Start session to check if the user is logged in
session_start();
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
            background: url('Images/realib.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #333333;
        }

      
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .container h1 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #153259;
        }

        .welcome-message {
            font-size: 16px;
            color: #555555;
            margin-bottom: 20px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .button-group a {
            text-decoration: none;
            padding: 12px 20px;
            background-color: #153259;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .button-group a:hover {
            background-color: #1251a0;
            transform: scale(1.05);
        }

        
        footer {
            background-color: #153259;
            color: #d6e4ef;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 15px;
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
            <h1>Welcome to the Book Reservation System</h1>

            <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
                <div class="welcome-message">
                    Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! What would you like to do today?
                </div>
                <div class="button-group">
                    <a href="search.php">Search Books</a>
                    <a href="reserve.php">Reserve a Book</a>
                    <a href="view_reserved.php">View My Reservations</a>
                    <a href="logout.php">Logout</a>
                </div>
            <?php else: ?>
                <div class="welcome-message">
                    Welcome! Please login or register to access the system.
                </div>
                <div class="button-group">
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

 
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Reservation System. All rights reserved.</p>
    </footer>

</body>
</html>

