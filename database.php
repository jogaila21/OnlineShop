<?php
$servername = "localhost";
$username = "User";
$password = "Password123";
$dbname = "MSU_Project";

try {
    // Create a PDO connection with a UTF-8 charset
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception for robust error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally, remove or comment out the following echo in production:
    // echo "Connected successfully";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
