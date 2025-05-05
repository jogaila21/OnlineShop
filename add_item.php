<?php
session_start();
include 'header.php';


// Verify user is logged in
if (!isset($_SESSION['UserID'])) {
    http_response_code(401);
    echo "You must be logged in to add items to the cart.";
    exit();
}

// DB credentials
$servername   = "localhost";
$db_username  = "User";
$db_password  = "Password123";
$dbname       = "MSU_Project";

// Connect
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed.";
    exit();
}

$user_id = $_SESSION['UserID'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ProductID'])) {
    $product_id = intval($_POST['ProductID']);

    $stmt = $conn->prepare("SELECT CartID, Quantity FROM Cart WHERE UserID = ? AND ProductID = ?");
    if (!$stmt) {
        http_response_code(500);
        echo "Query error.";
        exit();
    }

    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_quantity = $row['Quantity'] + 1;
        $stmt->close();

        $update_stmt = $conn->prepare("UPDATE Cart SET Quantity = ? WHERE CartID = ?");
        $update_stmt->bind_param("ii", $new_quantity, $row['CartID']);
        $update_stmt->execute();
        $update_stmt->close();

        echo "Cart updated successfully.";
    } else {
        $stmt->close();
        $insert_stmt = $conn->prepare("INSERT INTO Cart (UserID, ProductID, Quantity) VALUES (?, ?, 1)");
        $insert_stmt->bind_param("ii", $user_id, $product_id);
        $insert_stmt->execute();
        $insert_stmt->close();

        echo "Item added to cart.";
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}

$conn->close();
?>