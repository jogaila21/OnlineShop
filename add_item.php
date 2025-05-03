<?php
session_start();
// Verify the user is logged in using the correct session key.
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

// Database credentials.
$servername   = "localhost";
$db_username  = "User";
$db_password  = "Password123";
$dbname       = "MSU_Project";

// Create connection.
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['UserID'];

// Ensure the request method is POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if ProductID is provided in the form.
    if (isset($_POST['ProductID'])) {
        $product_id = intval($_POST['ProductID']);

        // Check if the product is already in the user's cart.
        $stmt = $conn->prepare("SELECT CartID, Quantity FROM Cart WHERE UserID = ? AND ProductID = ?");
        if (!$stmt) {
            die("Statement preparation failed: " . $conn->error);
        }
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If the product exists in the cart, update the quantity (increase by one).
            $row = $result->fetch_assoc();
            $new_quantity = $row['Quantity'] + 1;
            $stmt->close();

            $update_stmt = $conn->prepare("UPDATE Cart SET Quantity = ? WHERE CartID = ?");
            $update_stmt->bind_param("ii", $new_quantity, $row['CartID']);
            if ($update_stmt->execute()) {
                echo "Cart updated successfully.";
            } else {
                echo "Error updating cart: " . $update_stmt->error;
            }
            $update_stmt->close();
        } else {
            // If product is not yet in the cart, insert a new cart record.
            $stmt->close();

            $insert_stmt = $conn->prepare("INSERT INTO Cart (UserID, ProductID, Quantity) VALUES (?, ?, 1)");
            $insert_stmt->bind_param("ii", $user_id, $product_id);
            if ($insert_stmt->execute()) {
                echo "Item added to cart successfully.";
            } else {
                echo "Error adding item to cart: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        }
    } else {
        echo "No product selected to add to cart.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
