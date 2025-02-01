<?php
require_once 'DB_connect.php';
ob_clean(); 

header('Content-Type: application/json');

if (isset($_POST['country_id']) && $_POST['country_id'] !== "") {
    $countryID = $_POST['country_id'];

    $travelSql = "SELECT travel.id, user.firstName, user.lastName, user.photoFileName, 
                          country.country AS countryName, travel.month, travel.year, 
                          (SELECT COUNT(*) FROM likee WHERE placeID IN (SELECT id FROM place WHERE travelID = travel.id)) AS totalLikes
                  FROM travel 
                  JOIN user ON travel.userID = user.id 
                  JOIN country ON travel.countryID = country.id
                  WHERE country.id = ?";
    $stmt = $connection->prepare($travelSql);
    $stmt->bind_param('i', $countryID);
} else {
    $travelSql = "SELECT travel.id, user.firstName, user.lastName, user.photoFileName, 
                          country.country AS countryName, travel.month, travel.year, 
                          (SELECT COUNT(*) FROM likee WHERE placeID IN (SELECT id FROM place WHERE travelID = travel.id)) AS totalLikes
                  FROM travel 
                  JOIN user ON travel.userID = user.id 
                  JOIN country ON travel.countryID = country.id";
    $stmt = $connection->prepare($travelSql);
}

$stmt->execute();
$result = $stmt->get_result();
$travels = [];

while ($travel = $result->fetch_assoc()) {
    $travels[] = $travel;
}

echo json_encode($travels);

$stmt->close();
$connection->close();
?>
