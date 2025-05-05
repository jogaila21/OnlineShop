<?php
session_start();

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

require 'database.php';

$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($searchQuery !== '') {
    $searchQuery = htmlspecialchars($searchQuery);
    header("Location: result.php?query=" . urlencode($searchQuery));
    exit();
} else {
    // Display an error message instead of redirecting
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Search Error</title>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                Please enter a search query.
            </div>
        </div>
    </body>
    </html>";
    exit();
}
?>