<?php
// Start session
session_start();

// Destroy current session to log the user out
session_destroy();

// Redirect user to home page
header("Location: index.php");
exit;
?>
