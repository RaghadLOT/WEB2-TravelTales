<?php
//require 'DB_connect.php';
//include 'sign-inphp.php';
//?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" type="image/png" href="tab-logo.png">
   <style>
        body {
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    background-color: #d1cdc6;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('postcards-bg-blur.jpg') left/cover no-repeat;
}


.signup-container {
    width: 90vh;
    background: rgba(255, 255, 255, 0.8);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #392e27;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;

}

label {
    display: block;
    font-size: 14px;
    color: #4a4f3d; 
    margin-bottom: 0;
}

input[type="text"], input[type="email"], input[type="password"], input[type="file"] {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}

input[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #4A4F3D; 
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #3f4534; 
}

.signup-container form {
    display: flex;
    flex-direction: column;
    
}

.signup-container form .form-group input:focus {
    border-color: #4a4f3d; 
    outline: none;
}
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create a New Account</h2>
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 'email_exists') {
            echo "<p style='color:red;'>This email address is already registered. Please use a different email.</p>";
        }
        ?>
      
        <form id="signupForm" action="sign-inphp.php" method="POST">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="photo">Upload Photo (Optional)</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            <div class="form-group">
                <input type="submit" value="Sign Up">
            </div>
        </form>
    </div>

    

    
</body>
</html>