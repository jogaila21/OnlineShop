<?php
session_start();
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check if username and password fields are not empty
    if (empty($username) || empty($password)) {
        die("Please enter both a username and a password.");
    }

    try {
        // Prepare SQL statement with a named parameter
        $sql = "SELECT UserID, Username, PasswordHash FROM Users WHERE Username = :username";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement.");
        }

        // Bind the username parameter
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the user row from the result set
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // If a user with the entered username exists
        if ($row !== false) {
            // Verify the submitted password matches the stored password hash
            if (password_verify($password, $row['PasswordHash'])) {
                // Password is correct, start the user session
                $_SESSION['UserID'] = $row['UserID'];
                $_SESSION['Username'] = $row['Username'];
                
                // Redirect to a protected page, e.g., index.php
                header("Location: index.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that username.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
