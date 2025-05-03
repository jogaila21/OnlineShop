<?php
session_start();
require('database.php');

try {
    // Prepare and execute the query to fetch products
    $sql = "SELECT * FROM Product";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all rows as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display logout and users buttons
    echo "<div style='margin-bottom: 20px;'>
            <form action='logout.php' method='POST' style='display:inline;'>
                <input type='submit' value='Logout'>
            </form>
            <form action='admin_users.php' method='GET' style='display:inline;'>
                <input type='submit' value='Users'>
            </form>
          </div>";

    // Check if there are products to display
    if ($products) {
        echo "<table border='1'>";
        echo "<tr>
                <th>ProductID</th>
                <th>Category</th>
                <th>Description</th>
                <th>Name</th>
                <th>Price</th>
                <th>Brand</th>
                <th>Image</th>
                <th>Actions</th>
              </tr>";

        foreach ($products as $product) {
            echo "<tr>
                    <td>{$product['ProductID']}</td>
                    <td>{$product['Category']}</td>
                    <td>{$product['Description']}</td>
                    <td>{$product['Name']}</td>
                    <td>{$product['Price']}</td>
                    <td>{$product['Brand']}</td>
                    <td><img src='" . htmlspecialchars($product['ImagePath']) . "' width='100' alt='Product Image'></td>
                    <td>
                        <form action='edit_product.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='ProductID' value='{$product['ProductID']}'>
                            <input type='submit' value='Edit'>
                        </form>
                        <form action='delete_product.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='ProductID' value='{$product['ProductID']}'>
                            <input type='submit' value='Delete' onclick='return confirm(\"Are you sure?\");'>
                        </form>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "No products found.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
