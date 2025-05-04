<?php
session_start();

// Check if user is logged in, if not, redirect to login
if (!isset($_SESSION['User_ID'])) {  // Make sure the session variable matches the login session variable
    header('Location: login.php');
    exit();
}

require 'database.php';  // Make sure this is connecting to the correct database

// Handle search query, sanitize it to prevent security issues
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

// Debugging: Check if session is correct
// var_dump($_SESSION);  // Uncomment this line for debugging

// Redirect to result.php with the search query if there’s any, or back to shop.php
if ($searchQuery !== '') {
    // Sanitize the search query to avoid security issues
    $searchQuery = htmlspecialchars($searchQuery);
    header("Location: result.php?query=" . urlencode($searchQuery));
    exit();
} else {
    header("Location: shop.php");
    exit();
}
?>