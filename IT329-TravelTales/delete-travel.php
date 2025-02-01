<?php
require('DB_connect.php');
require_once 'check_login.php';

if (!isset($_SESSION['userID'])) {
    header("Location: user-travel.php");
    exit();
}

if (isset($_GET['travelID'])) {
    $travelID = intval($_GET['travelID']);
    
    mysqli_begin_transaction($connection);

    try {
        
        $deleteLikesSQL = "DELETE FROM likee 
                           WHERE placeID IN (SELECT id FROM place WHERE travelID = ?)";
        
        if ($statement = mysqli_prepare($connection, $deleteLikesSQL)) {
            mysqli_stmt_bind_param($statement, 'i', $travelID);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
        } else {
            throw new Exception("SQL statement preparation failed: " . mysqli_error($connection));
        }

        $deleteCommentsSQL = "DELETE FROM comment 
                               WHERE placeID IN (SELECT id FROM place WHERE travelID = ?)";
        
        if ($statement = mysqli_prepare($connection, $deleteCommentsSQL)) {
            mysqli_stmt_bind_param($statement, 'i', $travelID);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
        } else {
            throw new Exception("SQL statement preparation failed: " . mysqli_error($connection));
        }


        $deletePlacesSQL = "DELETE FROM place 
                            WHERE travelID = ?";
        
        if ($statement = mysqli_prepare($connection, $deletePlacesSQL)) {
            mysqli_stmt_bind_param($statement, 'i', $travelID);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
        } else {
            throw new Exception("SQL statement preparation failed: " . mysqli_error($connection));
        }

        $deleteTravelSQL = "DELETE FROM travel WHERE id = ?";
        if ($statement = mysqli_prepare($connection, $deleteTravelSQL)) {
            mysqli_stmt_bind_param($statement, 'i', $travelID);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
        } else {
            throw new Exception("SQL statement preparation failed: " . mysqli_error($connection));
        }

        mysqli_commit($connection);
        
        header("Location: user-travel.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($connection);
        echo "Error occurred: " . $e->getMessage();
    }
} 

mysqli_close($connection);
?>