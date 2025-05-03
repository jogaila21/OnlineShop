<?php
session_start();

// Check if the user is logged in.
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['CartID'])) {
    // If no CartID was provided, redirect back to cart.
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['UserID'];
$cartId = $_POST['CartID'];

// Database credentials.
$servername   = "localhost";
$db_username  = "User";
$db_password  = "Password123";
$dbname       = "MSU_Project";

// Create connection using mysqli.
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the DELETE statement ensuring that the cart item belongs to the current user.
$sql = "DELETE FROM Cart WHERE CartID = ? AND UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cartId, $user_id);
$stmt->execute();

// Optionally, you can check if the deletion was successful:
// if ($stmt->affected_rows > 0) { ... }

$stmt->close();
$conn->close();

// Redirect back to the cart page after removal.
header("Location: cart.php");
exit();
?>
