<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        h1 {
            color: #333;
        }
        .button-container {
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 18px;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Welcome to Comp Bros online shop<br> Please sign in to access the shop</h1>

    <div class="button-container">
        <form action="login.php" method="GET">
            <button class="button">Log In</button>
        </form>
        <form action="signup.php" method="GET">
            <button class="button">Register</button>
        </form>
    </div>

</body>
</html>
