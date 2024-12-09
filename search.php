<?php
$page_title = "Search Books";
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
$search_results = [];
$message = "";

// Handle book reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['isbn'])) {
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $reserve_date = date("Y-m-d");

    // Update book reservation status
    $update_sql = "UPDATE Books SET Rese = 'Y' WHERE ISBN = '$isbn'";
    $insert_sql = "INSERT INTO Reservations (ISBN, Username, ReservedDate) VALUES ('$isbn', '$username', '$reserve_date')";

    if ($conn->query($update_sql) === TRUE && $conn->query($insert_sql) === TRUE) {
        $message = "Book reserved successfully!";
    } else {
        $message = "Error reserving book: " . $conn->error;
    }
}

// Handle search query
$items_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$total_results = 0;
$has_next_page = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_query'])) {
    $search_query = $conn->real_escape_string($_POST['search_query']);

    // Get total matching results for pagination
    $count_sql = "SELECT COUNT(*) AS total FROM Books 
                  WHERE (BookTitle LIKE '%$search_query%' 
                     OR Author LIKE '%$search_query%' 
                     OR ISBN LIKE '%$search_query%')
                    AND Rese = 'N'";
    $count_result = $conn->query($count_sql);
    $total_results = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_results / $items_per_page);

    // Fetch paginated search results
    $sql = "SELECT * FROM Books 
            WHERE (BookTitle LIKE '%$search_query%' 
               OR Author LIKE '%$search_query%' 
               OR ISBN LIKE '%$search_query%')
              AND Rese = 'N'
            LIMIT $items_per_page OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    }

    // Check if there is a next page
    $has_next_page = ($page * $items_per_page) < $total_results;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            color: #153259;
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
            font-size: 16px;
            font-weight: bold;
            background-color: #153259;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #1251a0;
        }

        .results {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            color: #153259;
        }

        .reserve-button {
            padding: 8px 12px;
            font-size: 14px;
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
            <h2>Search Books</h2>

            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" action="search.php">
                <div class="form-group">
                    <label for="search_query">Search by Title, Author, or ISBN</label>
                    <input type="text" id="search_query" name="search_query" placeholder="e.g., Harry Potter, Dan Brown, 123456" required>
                </div>
                <div class="form-group">
                    <button type="submit">Search</button>
                </div>
            </form>

            <div class="results">
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_query'])): ?>
                    <?php if (count($search_results) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ISBN</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Edition</th>
                                    <th>Year</th>
                                    <th>Reserve</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($search_results as $book): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
                                        <td><?php echo htmlspecialchars($book['BookTitle']); ?></td>
                                        <td><?php echo htmlspecialchars($book['Author']); ?></td>
                                        <td><?php echo htmlspecialchars($book['Edition']); ?></td>
                                        <td><?php echo htmlspecialchars($book['Year']); ?></td>
                                        <td>
                                            <form method="POST" action="search.php?page=<?php echo $page; ?>">
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
                                <a href="search.php?page=<?php echo $page - 1; ?>">Previous</a>
                            <?php endif; ?>
                            <?php if ($has_next_page): ?>
                                <a href="search.php?page=<?php echo $page + 1; ?>">Next</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-results">No books found for your search.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Reservation System. All rights reserved.</p>
    </footer>
</body>
</html>
