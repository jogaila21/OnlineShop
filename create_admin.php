<?php
// Include the database connection file
require('database.php');

// Admin user details
$username = 'admin5';
$fname = 'Admin';
$lname = 'Five';
$email = 'admin5@example.com';
$password = '111111'; // Plain password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
$phone = '123-456-7890';
$dob = '1990-01-01';
$userType = 'admin';

try {
    // Prepare the SQL statement to insert the new admin user
    $sql = "INSERT INTO Users (Username, FName, LName, Email, PasswordHash, Phone, DOB, UserType)
            VALUES (:username, :fname, :lname, :email, :passwordHash, :phone, :dob, :userType)";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters to the prepared statement
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':passwordHash', $hashedPassword);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':userType', $userType);
    
    // Execute the statement to insert the user
    $stmt->execute();
    
    echo "Admin user created successfully.";
} catch (PDOException $e) {
    // Catch any errors and display them
    echo "Error: " . $e->getMessage();
}
?>