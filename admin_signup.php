<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
        }
        label, input, select {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Admin Sign Up</h2>
    <form action="process_signup.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone">

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob">

        <label for="user_type">Role:</label>
        <select id="user_type" name="user_type" required>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
