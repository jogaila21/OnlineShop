<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['UserID'])) {
    try {
        // Retrieve UserID from the POST request
        $userID = $_POST['UserID'];

        // Prepare SQL statement to delete user
        $sql = "DELETE FROM Users WHERE UserID = :userID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

        // Execute the deletion query
        if ($stmt->execute()) {
            header("Location: admin_users.php"); // Redirect back after deletion
            exit();
        } else {
            echo "Error deleting user.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
