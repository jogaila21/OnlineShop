<?php
session_start();
require('database.php');

try {
    // Prepare and execute the query to fetch users
    $sql = "SELECT * FROM Users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all rows as an associative array
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display logout and products buttons
    echo "<div style='margin-bottom: 20px;'>
            <form action='logout.php' method='POST' style='display:inline;'>
                <input type='submit' value='Logout'>
            </form>
            <form action='admin_view.php' method='GET' style='display:inline;'>
                <input type='submit' value='Products'>
            </form>
          </div>";

    // Check if there are users to display
    if ($users) {
        echo "<table border='1'>";
        echo "<tr>
                <th>UserID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>User Type</th>
                <th>Actions</th>
              </tr>";

        foreach ($users as $user) {
            echo "<tr>
                    <td>{$user['UserID']}</td>
                    <td>{$user['Username']}</td>
                    <td>{$user['FName']}</td>
                    <td>{$user['LName']}</td>
                    <td>{$user['Email']}</td>
                    <td>{$user['Phone']}</td>
                    <td>{$user['DOB']}</td>
                    <td>{$user['UserType']}</td>
                    <td>
                        <form action='delete_user.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='UserID' value='{$user['UserID']}'>
                            <input type='submit' value='Delete' onclick='return confirm(\"Are you sure?\");'>
                        </form>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "No users found.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
