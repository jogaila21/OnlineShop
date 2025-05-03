<?php
session_start();

// Ensure the user is logged in.
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['UserID'];

// Database credentials.
$servername   = "localhost";
$db_username  = "User";
$db_password  = "Password123";
$dbname       = "MSU_Project";

// Create a new mysqli connection.
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Begin a transaction to ensure data consistency.
$conn->begin_transaction();

try {
    // 1. Retrieve all items in the user's cart along with product details.
    $stmt = $conn->prepare("SELECT Cart.CartID, Cart.Quantity, Product.ProductID, Product.Price 
                            FROM Cart 
                            JOIN Product ON Cart.ProductID = Product.ProductID 
                            WHERE Cart.UserID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        throw new Exception("Your cart is empty.");
    }
    
    $order_items = [];
    $total = 0;
    
    while ($row = $result->fetch_assoc()) {
        $order_items[] = $row;
        $total += $row['Price'] * $row['Quantity'];
    }
    $stmt->close();

    // 2. Insert a new record into Orders.
    // We insert the order with OrderStatus set directly to 'Processed'.
    $orderDate = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO Orders (UserID, OrderDate, TotalAmount, OrderStatus) VALUES (?, ?, ?, 'Processed')");
    if (!$stmt) {
        throw new Exception("Failed to prepare the order insertion query: " . $conn->error);
    }
    $stmt->bind_param("isd", $user_id, $orderDate, $total);
    $stmt->execute();
    
    if ($stmt->affected_rows <= 0) {
        throw new Exception("Failed to insert the order.");
    }
    
    // Retrieve the newly created OrderID.
    $order_id = $conn->insert_id;
    $stmt->close();

    // 3. Insert each order item into OrderItems.
    $stmt = $conn->prepare("INSERT INTO OrderItems (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Failed to prepare the order items insertion query: " . $conn->error);
    }
    
    foreach ($order_items as $item) {
        $product_id = $item['ProductID'];
        $quantity   = $item['Quantity'];
        $price      = $item['Price'];
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmt->execute();
        
        if ($stmt->affected_rows <= 0) {
            throw new Exception("Failed to insert an order item.");
        }
    }
    $stmt->close();

    // 4. Clear the user's cart.
    $stmt = $conn->prepare("DELETE FROM Cart WHERE UserID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Commit the transaction.
    $conn->commit();
    $conn->close();

    // 5. Redirect to order_history.php with the OrderID in the query string.
    header("Location: order_history.php?order_id=" . $order_id);
    exit();

} catch (Exception $e) {
    // Roll back the transaction if any error occurs.
    $conn->rollback();
    $conn->close();
    echo "Error processing order: " . htmlspecialchars($e->getMessage());
}
?>
