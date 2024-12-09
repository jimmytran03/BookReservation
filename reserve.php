<?php
$page_title = "Reserve a Book";
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
$message = "";

// Fetch all categories
$categories = $conn->query("SELECT * FROM Categories")->fetch_all(MYSQLI_ASSOC);

// Pagination and category selection
$selected_category = isset($_POST['category_id']) ? $conn->real_escape_string($_POST['category_id']) : (isset($_GET['category_id']) ? $_GET['category_id'] : null);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 5;
$offset = ($page - 1) * $items_per_page;

$available_books = [];
$total_books = 0;

if ($selected_category) {
    // Fetch books with pagination
    $sql = "SELECT * FROM Books WHERE Category = '$selected_category' AND Rese = 'N' LIMIT $items_per_page OFFSET $offset";
    $available_books = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

    // Total books for pagination
    $total_books_result = $conn->query("SELECT COUNT(*) AS total FROM Books WHERE Category = '$selected_category' AND Rese = 'N'");
    $total_books = $total_books_result->fetch_assoc()['total'];
    $total_pages = ceil($total_books / $items_per_page);
}

// Handle reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['isbn'])) {
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $reserve_date = date("Y-m-d");

    $conn->query("UPDATE Books SET Rese = 'Y' WHERE ISBN = '$isbn'");
    $conn->query("INSERT INTO Reservations (ISBN, Username, ReservedDate) VALUES ('$isbn', '$username', '$reserve_date')");

    $message = "Book reserved successfully!";
    header("Location: reserve.php?category_id=$selected_category&page=$page");
    exit;
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
            max-width: 700px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #153259;
            display: block;
            margin-bottom: 5px;
        }

        .form-group select, .form-group button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group button {
            background-color: #153259;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #1251a0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            color: #153259;
            font-weight: bold;
        }

        .reserve-button {
            padding: 8px 12px;
            background-color: #153259;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .reserve-button:hover {
            background-color: #1251a0;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .pagination a {
            text-decoration: none;
            padding: 8px 12px;
            background-color: #153259;
            color: #fff;
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
            <h2>Reserve a Book</h2>
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" action="reserve.php">
                <div class="form-group">
                    <label for="category_id">Select a Category</label>
                    <select name="category_id" id="category_id" onchange="this.form.submit()" required>
                        <option value="" disabled selected>-- Choose a Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['CategoryID']); ?>" 
                                <?php echo $selected_category == $category['CategoryID'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['CategoryDescription']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if ($selected_category): ?>
                <?php if (count($available_books) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Reserve</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($available_books as $book): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
                                    <td><?php echo htmlspecialchars($book['BookTitle']); ?></td>
                                    <td><?php echo htmlspecialchars($book['Author']); ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
                                            <button type="submit" class="reserve-button">Reserve</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="reserve.php?category_id=<?php echo $selected_category; ?>&page=<?php echo $page - 1; ?>">Previous Page</a>
                        <?php endif; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="reserve.php?category_id=<?php echo $selected_category; ?>&page=<?php echo $page + 1; ?>">Next Page</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="error">No books available in this category.</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Reservation System. All rights reserved.</p>
    </footer>
</body>
</html>
