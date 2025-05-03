<?php
require('database.php');

try {
    // Retrieve form inputs and sanitize if necessary
    $user       = $_POST['username'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name  = $_POST['last_name'] ?? '';
    $email      = $_POST['email'] ?? '';
    $pass       = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT);
    $phone      = $_POST['phone'] ?? '';
    $dob        = $_POST['dob'] ?? '';
    $user_type  = $_POST['user_type'] ?? 'User'; // Default to 'User' if not provided

    // Prepare SQL statement using named placeholders
    $sql = "INSERT INTO Users (Username, FName, LName, Email, PasswordHash, Phone, DOB, UserType) 
            VALUES (:username, :fname, :lname, :email, :password, :phone, :dob, :user_type)";
            
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $errorInfo = $conn->errorInfo();
        throw new Exception("Prepare failed: " . $errorInfo[2]);
    }

    // Bind parameters to the statement
    $stmt->bindParam(':username', $user, PDO::PARAM_STR);
    $stmt->bindParam(':fname', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':lname', $last_name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
    $stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);

    // Execute the prepared statement
    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Execution error: " . $errorInfo[2];
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
} catch (Exception $e) {
    echo "General error: " . $e->getMessage();
}
?>
