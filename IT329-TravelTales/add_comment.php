<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once 'DB_connect.php';

ob_clean();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['userID']) || !isset($_SESSION['userName'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    $userID = $_SESSION['userID'];
    $userName = htmlspecialchars($_SESSION['userName']); // Escape session data for safety
    $placeID = $_POST['place_id'] ?? null;
    $commentText = trim($_POST['comment_text'] ?? '');

    if (empty($commentText)) {
        echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
        exit;
    }

    if (!filter_var($placeID, FILTER_VALIDATE_INT)) {
        echo json_encode(['success' => false, 'message' => 'Invalid place ID']);
        exit;
    }

    $sql = "INSERT INTO comment (userID, placeID, comment) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL query']);
        exit;
    }

    $stmt->bind_param('iis', $userID, $placeID, $commentText);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'data' => [
                'userName' => $userName,
                'comment' => htmlspecialchars($commentText) // Escape for safe output
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save comment']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$connection->close();
?>
