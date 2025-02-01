<?php
session_start();
require('DB_connect.php');
require 'check_login.php';

$userID = $_SESSION['userID'];
$first_name = 'User';


$sql = "SELECT firstName FROM user WHERE id = ?";
if ($statement = mysqli_prepare($connection, $sql)) {
    mysqli_stmt_bind_param($statement, 'i', $userID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_bind_result($statement, $first_name);
    if (!mysqli_stmt_fetch($statement)) {
        $first_name = 'User';
    }
    mysqli_stmt_close($statement);
} else {
    die("SQL statement preparation failed: " . mysqli_error($connection));
}


$travels_sql = "
    SELECT t.id AS travel_id, t.month, t.year, c.country,
           p.name AS place_name, p.location, p.description,
           p.photoFileName,
           (SELECT COUNT(*) FROM likee WHERE placeID = p.id) AS likes_count,
           (SELECT GROUP_CONCAT(c.comment SEPARATOR '<br>') FROM comment c WHERE c.placeID = p.id) AS comments
    FROM travel t
    JOIN country c ON t.countryID = c.id
    LEFT JOIN place p ON t.id = p.travelID
    WHERE t.userID = ?
    ORDER BY t.id";

if ($travels_statement = mysqli_prepare($connection, $travels_sql)) {
    mysqli_stmt_bind_param($travels_statement, 'i', $userID);
    mysqli_stmt_execute($travels_statement);
    $travels_result = mysqli_stmt_get_result($travels_statement);
    mysqli_stmt_close($travels_statement);
} else {
    die("SQL statement preparation failed: " . mysqli_error($connection));
}
?>