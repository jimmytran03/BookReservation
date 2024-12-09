<?php
$page_title = "My Reservations";
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

// Ensure user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username']; // Logged-in user's username

// Pagination setup
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 5;
$offset = ($page - 1) * $items_per_page;

// Fetch reserved books with pagination
$sql = "SELECT r.ISBN, b.BookTitle, b.Author, r.ReservedDate
        FROM Reservations r
        JOIN Books b ON r.ISBN = b.ISBN
        WHERE r.Username = '$username'
        LIMIT $items_per_page OFFSET $offset";
$result = $conn->query($sql);
$reserved_books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reserved_books[] = $row;
    }
}

// Fetch total number of reservations for pagination
$total_reservations_sql = "SELECT COUNT(*) AS total FROM Reservations WHERE Username = '$username'";
$total_reservations_result = $conn->query($total_reservations_sql);
$total_reservations = $total_reservations_result->fetch_assoc()['total'];
$total_pages = ceil($total_reservations / $items_per_page);

// Handle cancellation of reservation
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['isbn'])) {
    $isbn = $conn->real_escape_string($_POST['isbn']);

    // Remove reservation
    $delete_sql = "DELETE FROM Reservations WHERE ISBN = '$isbn' AND Username = '$username'";
    $update_sql = "UPDATE Books SET Rese = 'N' WHERE ISBN = '$isbn'";

    if ($conn->query($delete_sql) === TRUE && $conn->query($update_sql) === TRUE) {
        $message = "Reservation canceled successfully!";
        header("Location: view_reserved.php?page=$page");
        exit;
    } else {
        $message = "Error canceling reservation: " . $conn->error;
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('images/realib.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .main-content {
            flex: 1;
            padding: 20px 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #153259;
            margin-bottom: 20px;
        }

        .message, .error {
            text-align: center;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .message {
            color: green;
        }

        .error {
            color: red;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f4f4f4;
            color: #153259;
            font-weight: bold;
        }

        .cancel-button {
            padding: 8px 12px;
            font-size: 14px;
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-button:hover {
            background-color: #c9302c;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .pagination a {
            text-decoration: none;
            padding: 8px 12px;
            font-size: 14px;
            background-color: #153259;
            color: white;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #1251a0;
        }

        footer {
            background-color: #153259;
            color: #d6e4ef;
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
            text-decoration: underline;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <h2>My Reservations</h2>

            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if (count($reserved_books) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Reserved Date</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reserved_books as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
                                <td><?php echo htmlspecialchars($book['BookTitle']); ?></td>
                                <td><?php echo htmlspecialchars($book['Author']); ?></td>
                                <td><?php echo htmlspecialchars($book['ReservedDate']); ?></td>
                                <td>
                                    <form method="POST" action="view_reserved.php?page=<?php echo $page; ?>">
                                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
                                        <button type="submit" class="cancel-button">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="view_reserved.php?page=<?php echo $page - 1; ?>">Previous Page</a>
                    <?php endif; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="view_reserved.php?page=<?php echo $page + 1; ?>">Next Page</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="error">You have no reservations.</div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Reservation System. All rights reserved.</p>
    </footer>
</body>
</html>
