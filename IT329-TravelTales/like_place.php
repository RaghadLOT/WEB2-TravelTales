<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'DB_connect.php';

ob_clean();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placeID'])) {
    $placeID = $_POST['placeID'];
    $userID = $_SESSION['userID']; 

    
    $checkLikeSql = "SELECT COUNT(*) AS likeCount FROM likee WHERE userID = ? AND placeID = ?";
    $checkLikeStmt = $connection->prepare($checkLikeSql);
    $checkLikeStmt->bind_param('ii', $userID, $placeID);
    $checkLikeStmt->execute();
    $checkLikeResult = $checkLikeStmt->get_result();
    $likeCount = $checkLikeResult->fetch_assoc()['likeCount'];

    if ($likeCount == 0) {
        
        $likeSql = "INSERT INTO likee (userID, placeID) VALUES (?, ?)";
        $likeStmt = $connection->prepare($likeSql);
        $likeStmt->bind_param('ii', $userID, $placeID);
        $likeStmt->execute();

        
        $totalLikesSql = "SELECT COUNT(*) AS totalLikes FROM likee WHERE placeID = ?";
        $totalLikesStmt = $connection->prepare($totalLikesSql);
        $totalLikesStmt->bind_param('i', $placeID);
        $totalLikesStmt->execute();
        $totalLikesResult = $totalLikesStmt->get_result();
        $totalLikes = $totalLikesResult->fetch_assoc()['totalLikes'];
        
        echo json_encode(['success' => true, 'totalLikes' => $totalLikes]);
    } else {
        
        echo json_encode(['success' => false, 'message' => 'You have already liked this place.']);
    }

    $likeStmt->close();
    $checkLikeStmt->close();
    $totalLikesStmt->close();
} else {
    
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$connection->close(); 
?>