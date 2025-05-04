<?php
session_start();
include 'header.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

require 'database.php'; // sets up $conn as a PDO instance

$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($searchQuery !== '') {
    $search = "%" . $searchQuery . "%";
    $stmt = $conn->prepare("
        SELECT * FROM product 
        WHERE Name LIKE :search OR Description LIKE :search 
        OR Category LIKE :search OR ProductID LIKE :search
    ");
    $stmt->bindValue(':search', $search, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<div class='alert alert-warning container mt-5'>Please enter a search query.</div>";
    exit();
}
?>

<div class="container mt-5">
    <h1>Search Results</h1>

    <?php if ($results && count($results) > 0): ?>
        <p><?php echo count($results); ?> results found for '<strong><?php echo htmlspecialchars($_GET['query']); ?></strong>'</p>
        <div class="row">
        <?php foreach ($results as $row): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <!-- Product image -->
                    <?php if (!empty($row['ImagePath'])): ?>
                        <img src="/images/<?php echo htmlspecialchars($row['ImagePath']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['Name']); ?>">
                    <?php else: ?>
                        <img src="/images/default.jpg" class="card-img-top" alt="No image available">
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['Name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['Description']); ?></p>
                        <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($row['Category']); ?></p>
                        <p class="card-text"><strong>Price:</strong> $<?php echo number_format($row['Price'], 2); ?></p>
                    </div>
                    <div class="card-footer">
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['ProductID']; ?>">
                            <button type="submit" class="btn btn-success btn-block">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No results found for '<strong><?php echo htmlspecialchars($_GET['query']); ?></strong>'.</div>
    <?php endif; ?>
</div>