<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Shop</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
    <a class="navbar-brand" href="index.php">
        <img src="logo.jpg" width="50" height="50" alt="Logo"> Computer Bros
    </a>
    <div class="collapse navbar-collapse">
        <form class="form-inline my-2 my-lg-0 ml-5" action="search.php" method="GET">
            <input class="form-control mr-sm-2" type="search" placeholder="Search products..." name="query">
            <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <ul class="navbar-nav ml-3">
            <li class="nav-item">
                <a class="btn btn-success" href="index.php">Home</a>
            </li>
            <li class="nav-item ml-2">
                <a class="btn btn-success" href="all_products.php">All Products</a>
            </li>
            <li class="nav-item ml-2">
                <a class="btn btn-success" href="about_us.php">About us</a>
            </li>
            <li class="nav-item dropdown ml-2">
                <a class="btn btn-success dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown">
                    Categories
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="category.php?category=Laptop">Laptops</a>
                    <a class="dropdown-item" href="category.php?category=Networking">Network Components</a>
                    <a class="dropdown-item" href="category.php?category=Monitor">Monitors and Displays</a>
                    <a class="dropdown-item" href="category.php?category=Mouse">Peripherals</a>
                    <a class="dropdown-item" href="category.php?category=Keyboard">Keyboard</a>
                    <a class="dropdown-item" href="category.php?category=Storage">Storage Hardware</a>
                </div>
            </li>
            <li class="nav-item ml-2">
                <a class="btn btn-success" href="cart.php">Cart</a>
            </li>
            <li class="nav-item dropdown ml-2">
                <a class="btn btn-success dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                    <i class="fas fa-user"></i>
                </a>
                <div class="dropdown-menu">
    <a class="dropdown-item" href="orders.php">Dashboard</a>

    <?php if (isset($_SESSION['UserType']) && strtolower($_SESSION['UserType']) === 'admin'): ?>
        <a class="dropdown-item" href="admin_view.php">Admin Page</a>
    <?php endif; ?>

    <a class="dropdown-item" href="logout.php">Logout</a>
</div>
            </li>
        </ul>
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
