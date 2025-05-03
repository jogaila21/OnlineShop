<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ProductID'])) {
    $productID = $_POST['ProductID'];

    // Fetch existing product details
    $sql = "SELECT * FROM Product WHERE ProductID = :productID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Retrieve updated details
    $productID = $_POST['ProductID'];
    $category = $_POST['Category'];
    $description = $_POST['Description'];
    $name = $_POST['Name'];
    $price = $_POST['Price'];
    $brand = $_POST['Brand'];
    $imagePath = $_POST['ImagePath'];

    // Update product details in the database
    $sql = "UPDATE Product SET Category = :category, Description = :description, Name = :name,
            Price = :price, Brand = :brand, ImagePath = :imagePath WHERE ProductID = :productID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_STR);
    $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
    $stmt->bindParam(':imagePath', $imagePath, PDO::PARAM_STR);
    $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect back
        exit();
    } else {
        echo "Error updating product.";
    }
}
?>

<!-- Edit Form -->
<form method="POST">
    <input type="hidden" name="ProductID" value="<?= htmlspecialchars($product['ProductID']) ?>">
    Category: <input type="text" name="Category" value="<?= htmlspecialchars($product['Category']) ?>"><br>
    Description: <textarea name="Description"><?= htmlspecialchars($product['Description']) ?></textarea><br>
    Name: <input type="text" name="Name" value="<?= htmlspecialchars($product['Name']) ?>"><br>
    Price: <input type="text" name="Price" value="<?= htmlspecialchars($product['Price']) ?>"><br>
    Brand: <input type="text" name="Brand" value="<?= htmlspecialchars($product['Brand']) ?>"><br>
    Image Path: <input type="text" name="ImagePath" value="<?= htmlspecialchars($product['ImagePath']) ?>"><br>
    <input type="submit" name="update" value="Update Product">
</form>
