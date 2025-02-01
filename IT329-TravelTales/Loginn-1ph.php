<?php
session_start();

require_once 'DB_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $connection->prepare("SELECT id, password, firstName FROM user WHERE emailAddress = ?");
    $query->bind_param('s', $email);
    $query->execute();
    $query->store_result();
    
    if ($query->num_rows > 0) {
        $query->bind_result($user_id, $hashed_password, $firstName); 
        $query->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['userID'] = $user_id;
            $_SESSION['userName'] = $firstName; 
            header("Location: UserHome.php");
            exit();
        } else {
            header("Location: Loginn-1PHP.php?error=Invalid password. Please try again.");
            exit();
        }
    } else {
        header("Location: Loginn-1PHP.php?error=Email not found. Please check and try again.");
        exit();
    }
    
    $connection->close();
}
