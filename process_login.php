<?php
session_start();
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        die("Please enter both a username and a password.");
    }

    try {
        $sql = "SELECT UserID, Username, PasswordHash, UserType FROM Users WHERE Username = :username";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement.");
        }

        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo "No user found with that username.";
        } else {
            if (password_verify($password, $row['PasswordHash'])) {
                // Set session variables
                $_SESSION['UserID'] = $row['UserID'];
                $_SESSION['Username'] = $row['Username'];
                $_SESSION['UserType'] = $row['UserType'];

                // Check UserType and redirect accordingly
                if (strcasecmp($row['UserType'], 'admin') === 0) {
                    header("Location: admin_view.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                echo "Invalid password.";
            }
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