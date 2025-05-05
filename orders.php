<?php
session_start();
require 'database.php';
include 'header.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['UserID'];

try {
    // Fetch orders for this user
    $stmtOrders = $conn->prepare("SELECT * FROM Orders WHERE UserID = :userId ORDER BY OrderDate DESC");
    $stmtOrders->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmtOrders->execute();
    $orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='text-danger'>Error fetching orders: " . $e->getMessage() . "</p>";
    exit();
}
?>

<div class="container mt-5">
    <h2>Your Order History</h2>

    <?php if (count($orders) === 0): ?>
        <p>You have not placed any orders yet.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Order ID:</strong> <?php echo $order['OrderID']; ?> |
                    <strong>Date:</strong> <?php echo $order['OrderDate']; ?> |
                    <strong>Total:</strong> $<?php echo number_format($order['TotalAmount'], 2); ?>
                </div>
                <div class="card-body">
                    <?php
                    // Fetch items for this order
                    $stmtItems = $conn->prepare("
                        SELECT oi.Quantity, oi.Price, p.Name, p.ImagePath 
                        FROM OrderItems oi 
                        JOIN Product p ON oi.ProductID = p.ProductID 
                        WHERE oi.OrderID = :orderId
                    ");
                    $stmtItems->bindValue(':orderId', $order['OrderID'], PDO::PARAM_INT);
                    $stmtItems->execute();
                    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <ul class="list-group">
                        <?php foreach ($items as $item): ?>
                            <li class="list-group-item d-flex align-items-center">
                                <img src="<?php echo htmlspecialchars($item['ImagePath']); ?>" alt="Product" style="width: 60px; height: auto; margin-right: 15px;">
                                <div>
                                    <strong><?php echo htmlspecialchars($item['Name']); ?></strong><br>
                                    Quantity: <?php echo $item['Quantity']; ?> | 
                                    Price: $<?php echo number_format($item['Price'], 2); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
