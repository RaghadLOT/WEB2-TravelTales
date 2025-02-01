<?php
require_once 'DB_connect.php';
include 'Loginn-1ph.php';

$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="tab-logo.png">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Trebuchet MS', 'Lucida Sans', Arial, sans-serif;
            background: url('postcards-bg-blur.jpg') left/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
            position: relative;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            color: #392e27;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #191a1e;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4a4f3d;
            color: white;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45473e;
        }

        .register {
            text-align: center;
            margin-top: 20px;
        }

        .register a {
            color: #4a4f3d;
            text-decoration: none;
            font-weight: bold;
        }

        .register a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="Loginn-1ph.php" method="POST">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" placeholder="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="password" required>
            
            <button type="submit">Login</button>
        </form>

        <div class="register">
            <p>Not registered? <a href="sign-in.php">Create an account</a></p>
        </div>
    </div>
</body>
</html>