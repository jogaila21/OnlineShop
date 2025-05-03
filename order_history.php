<?php
session_start();

// Ensure the user is logged in.
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['UserID'];

// Database credentials.
$servername  = "localhost";
$db_username = "User";
$db_password = "Password123";
$dbname      = "MSU_Project";

// Create a new mysqli connection.
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the username for the current user.
$stmtUser = $conn->prepare("SELECT Username FROM Users WHERE UserID = ?");
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
if ($userResult->num_rows > 0) {
    $userData = $userResult->fetch_assoc();
    $username = $userData['Username'];
} else {
    $username = "Unknown";
}
$stmtUser->close();

if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Retrieve the order details for this order and user.
    $stmt = $conn->prepare("SELECT OrderID, OrderDate, TotalAmount, OrderStatus FROM Orders WHERE OrderID = ? AND UserID = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $order_result = $stmt->get_result();
    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
    } else {
        echo "No order found.";
        exit();
    }
    $stmt->close();
    
    // Retrieve the order items with product details.
    $stmt = $conn->prepare("SELECT oi.*, p.Name, p.ImagePath 
                            FROM OrderItems oi 
                            JOIN Product p ON oi.ProductID = p.ProductID 
                            WHERE oi.OrderID = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_items_result = $stmt->get_result();
    
} else {
    echo "Order ID not provided.";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .order-details, .order-items, .redirect {
            max-width: 800px;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #f2f2f2;
        }
        img {
            width: 50px;
            height: auto;
        }
        .redirect {
            text-align: center;
        }
        .redirect a button {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="order-details">
        <h2>Order Details</h2>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['OrderID']); ?></p>
        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['OrderDate']); ?></p>
        <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars(number_format($order['TotalAmount'], 2)); ?></p>
        <p><strong>Order Status:</strong> <?php echo htmlspecialchars($order['OrderStatus']); ?></p>
        <!-- Show the username instead of the user ID -->
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
    </div>
    <div class="order-items">
        <h2>Ordered Items</h2>
        <?php if ($order_items_result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $total = 0;
            while ($item = $order_items_result->fetch_assoc()) {
                $subtotal = $item['Price'] * $item['Quantity'];
                $total += $subtotal;
                echo "<tr>";
                echo "<td><img src='" . htmlspecialchars($item['ImagePath']) . "' alt='" . htmlspecialchars($item['Name']) . "' /></td>";
                echo "<td>" . htmlspecialchars($item['Name']) . "</td>";
                echo "<td>" . htmlspecialchars($item['Quantity']) . "</td>";
                echo "<td>$" . number_format($item['Price'], 2) . "</td>";
                echo "<td>$" . number_format($subtotal, 2) . "</td>";
                echo "</tr>";
            }
            ?>
            <tr>
                <td colspan="4" align="right"><strong>Total:</strong></td>
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </table>
        <?php } else { ?>
        <p>No items found for this order.</p>
        <?php } ?>
    </div>
    <div class="redirect">
        <!-- Button to redirect to user_view.php -->
        <a href="user_view.php"><button type="button">Continue Shopping</button></a>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
