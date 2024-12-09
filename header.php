<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : "Book Reservation System"; ?></title>
    <style>
        
        body {
            margin: 0;
            font-family: Arial, sans-serif; 
            background-color: #f8f9fa; 
            color: #333333; 
            line-height: 1.6;
        }

        
        .header {
            background-color: #153259; 
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: bold;
        }

        .header nav {
            margin-top: 15px;
        }

        .header a {
            color: #85c1e9; /* Light blue for links */
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            margin: 0 12px;
            transition: color 0.3s, border-bottom 0.3s;
            padding-bottom: 3px;
            display: inline-block;
        }

        .header a:hover {
            color: #ffffff;
            border-bottom: 2px solid #85c1e9; 
        }

        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 24px;
            }

            .header a {
                font-size: 14px;
                margin: 0 8px;
            }
        }
    </style>
</head>
<body>
    
    <div class="header">
        <h1>Book Reservation System</h1>
        <nav>
            <a href="login.php">Home</a>
            <a href="search.php">Search Books</a>
            <a href="reserve.php">Reserve a Book</a>
            <a href="view_reserved.php">View Reservations</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</body>
</html>

