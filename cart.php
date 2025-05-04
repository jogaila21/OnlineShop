<?php
session_start();

// Check if the user is logged in using the same session key as process_login.php.
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php"); // Redirect to login if not logged in.
    exit();
}
include 'header.php';
// Database credentials.
$servername   = "localhost";
$db_username  = "User";         // As set up in your SQL script.
$db_password  = "Password123";
$dbname       = "MSU_Project";

// Create connection using mysqli.
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['UserID'];

// Prepare a query to retrieve the cart items along with their product details.
$sql = "SELECT Cart.CartID, Cart.Quantity, Product.ProductID, Product.Name, Product.Price, Product.ImagePath
        FROM Cart
        JOIN Product ON Cart.ProductID = Product.ProductID
        WHERE Cart.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        img { width: 50px; height: auto; }
        body { font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    <h2 align="center">Your Shopping Cart</h2>
    <?php
    if ($result->num_rows > 0) {
        $total = 0;
        echo "<table>";
        echo "<tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            $subtotal = $row['Price'] * $row['Quantity'];
            $total += $subtotal;
            echo "<tr>";
            echo "<td><img src='" . htmlspecialchars($row['ImagePath']) . "' alt='" . htmlspecialchars($row['Name']) . "' /></td>";
            echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
            echo "<td>$" . number_format($row['Price'], 2) . "</td>";
            echo "<td>" . $row['Quantity'] . "</td>";
            echo "<td>$" . number_format($subtotal, 2) . "</td>";
            echo "<td>
                    <form action='remove_item.php' method='post' style='display:inline;'>
                        <input type='hidden' name='CartID' value='" . $row['CartID'] . "' />
                        <input type='submit' value='Remove' />
                    </form>
                  </td>";
            echo "</tr>";
        }
        // Display total amount.
        echo "<tr>
                <td colspan='4'><strong>Total</strong></td>
                <td colspan='2'><strong>$" . number_format($total, 2) . "</strong></td>
              </tr>";
        echo "</table>";
        echo "<div align='center' style='margin-top:20px;'>
                <a href='process_order.php'>Proceed to Checkout</a>
              </div>";
    } else {
        echo "<p align='center'>No items have been added to your cart.</p>";
    }

    // Clean up.
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
