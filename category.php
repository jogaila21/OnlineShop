<?php
session_start();
require 'database.php';  // Ensure you are including your database connection

// Get the category from the URL (note the lowercase 'category' here)
$category = isset($_GET['category']) ? $_GET['category'] : ''; // lowercase 'category'

// Query to fetch products from the selected category
if ($category !== '') {
    $stmt = $conn->prepare("SELECT * FROM product WHERE Category = :Category"); // 'Category' with uppercase 'C' in the SQL query
    // Using bindValue for PDO to bind the category parameter
    $stmt->bindValue(':Category', $category, PDO::PARAM_STR); // bind to ':Category' with uppercase 'C'
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $result = false;
}

include 'header.php';
?>

<div class="container mt-5">
  <h2 class="mb-4">Products in Category: <em><?php echo htmlspecialchars($category); ?></em></h2>
  
  <?php if (!empty($_SESSION['message'])): ?>
    <div class="alert alert-success">
        <?php 
            echo $_SESSION['message']; 
            $_SESSION['message'] = ""; // Clear the message after displaying
        ?>
    </div>
  <?php endif; ?>

  <?php if ($result && count($result) > 0): ?>
    <div class="row">
      <?php foreach ($result as $row): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <?php if (!empty($row['ImagePath'])): ?>
              <img src="<?php echo htmlspecialchars($row['ImagePath']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['Name']); ?>">
            <?php else: ?>
                <img src="/images/default.jpg" class="card-img-top" alt="No image available">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($row['Name']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($row['Description']); ?></p>
              <p class="card-text"><strong>Price:</strong> $<?php echo number_format($row['Price'], 2); ?></p>
            </div>
            <div class="card-footer bg-white">
              <form action="add_item.php" method="POST">
                <input type="hidden" name="ProductID" value="<?php echo $row['ProductID']; ?>">
                <button type="submit" class="btn btn-success btn-block">Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No products found in the category "<strong><?php echo htmlspecialchars($category); ?></strong>".</p>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>