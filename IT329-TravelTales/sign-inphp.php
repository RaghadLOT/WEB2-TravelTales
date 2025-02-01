<?php
session_start();
ini_set('display_errors','1');

include 'DB_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $checkEmail = $connection->prepare("SELECT * FROM user WHERE emailAddress = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        header("Location: sign-in.php?error=email_exists");
        exit();
    }

    $photoPath = "def.png";

    $insertUser = $connection->prepare("INSERT INTO user (firstName, lastName, emailAddress, password, photoFileName) VALUES (?, ?, ?, ?, ?)");
    $insertUser->bind_param("sssss", $firstName, $lastName, $email, $password, $photoPath);

    if ($insertUser->execute()) {
        $userID = $insertUser->insert_id;

        if (!empty($_FILES['photo']['name'])) {
            $target_dir = "uploads/";
            $file_extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $uniqueFileName = "user_" . $userID . "_profile." . $file_extension;
            $target_file = $target_dir . $uniqueFileName;

            if (getimagesize($_FILES['photo']['tmp_name']) === false) {
                header("Location: sign-in.php?error=uploadfail");
                exit();
            }

            if ($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg") {
                header("Location: sign-in.php?error=uploadfail");
                exit();
            }

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                $photoPath = $uniqueFileName; 
            } else {
                header("Location: sign-in.php?error=uploadfail");
                exit();
            }
        }

        $updatePhoto = $connection->prepare("UPDATE user SET photoFileName = ? WHERE id = ?");
        $updatePhoto->bind_param("si", $photoPath, $userID);
        $updatePhoto->execute();

        $_SESSION['userID'] = $userID;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['email'] = $email;
        $_SESSION['photo'] = $photoPath;

        header("Location: UserHome.php");
        exit();
    } else {
        header("Location: sign-in.php?error=signupfail");
        exit();
    }
}

$connection->close();
