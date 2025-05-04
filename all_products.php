<?php
session_start();

// Verify that the user is logged in. We check for the session key 'UserID' because it's set in process_login.php.
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

require('database.php'); // This file should set up your $conn PDO connection.
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User View</title>
    <?php include 'header.php';?>
    <style>
        table { border-collapse: collapse; width: 90%; margin: auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        img { width: 100px; height: auto; }
        body { font-family: Arial, sans-serif; }
        .button-container { margin-bottom: 20px; text-align: center; }
        button { padding: 8px 16px; margin: 0 5px; }
    </style>
</head>
<body>    
    <?php
    try {
        // Prepare and execute the query to fetch products
        $sql = "SELECT * FROM Product";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Fetch all rows as an associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are products to display
        if ($products) {
            echo "<table>";
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
                        <td>\${$product['Price']}</td>
                        <td>{$product['Brand']}</td>
                        <td><img src='" . htmlspecialchars($product['ImagePath']) . "' alt='Product Image'></td>
                        <td>
                            <form action='add_item.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='ProductID' value='{$product['ProductID']}'>
                                <input type='submit' value='Purchase'>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p align='center'>No products found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error fetching products: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
