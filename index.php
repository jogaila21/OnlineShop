<html>
    <head>
        <?php include 'header.php'; ?>
        <!-- Add Bootstrap CDN link or your custom CSS file -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <style>
            /* Custom CSS to center the table */
            .center-table {
                margin: 0 auto; /* Centers the table horizontally */
                width: 80%; /* Optional: Set the width of the table (adjust as needed) */
                text-align: center; /* Centers the text in the cells */
            }
            .featured-header {
                background-color: #f8f9fa; /* Light gray background for the header row */
                font-weight: bold;
                font-size: 20px;
                text-align: center;
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <br>
        <a href="main.php">Main Page</a><br>
        <a href="login.php">Login Page</a><br>
        <a href="signup.php">Signup Page</a><br>
        <a href="main.php">Main Page</a><br>
        <a href="admin_signup.php">Admin Signup Page</a><br>
        <a href="admin_view.php">Admin Page</a><br>
        
        <?php
        // Database connection parameters
        $servername = "localhost";
        $username = "User";
        $password = "Password123";
        $dbname = "MSU_Project";

        // Create a connection to the MySQL database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to get the first three products
        $sql = "SELECT Category, Description, Name, Price, Brand, ImagePath FROM product LIMIT 3";  // Add LIMIT 3 to fetch only 3 products

        // Execute the query
        $result = $conn->query($sql);

        // Check if any results were returned
        if ($result->num_rows > 0) {
            // Start the table to display the products
            echo "<table class='table table-bordered center-table'>
                    <thead>
                        <tr class='featured-header'>
                            <td colspan='6'>Featured Products</td> <!-- Spanning across all columns -->
                        </tr>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Brand</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>";

            // Loop through the results and display each product
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Category"] . "</td>
                        <td>" . $row["Description"] . "</td>
                        <td>" . $row["Name"] . "</td>
                        <td>" . $row["Price"] . "</td>
                        <td>" . $row["Brand"] . "</td>
                        <td><img src='" . $row["ImagePath"] . "' alt='Product Image' width='100'></td>
                      </tr>";
            }

            // End the table
            echo "</tbody></table>";
        } else {
            echo "No products found.";
        }

        // Close the database connection
        $conn->close();
        ?>
    </body>
</html>