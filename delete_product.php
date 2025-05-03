<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ProductID'])) {
    $productID = $_POST['ProductID'];

    // Delete product from the database
    $sql = "DELETE FROM Product WHERE ProductID = :productID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: admin_view.php"); // Redirect back
        exit();
    } else {
        echo "Error deleting product.";
    }
}
?>
