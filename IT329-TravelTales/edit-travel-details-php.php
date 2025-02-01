<?php
session_start();
require 'DB_connect.php';
require_once 'check_login.php'; 

if (isset($_POST['updateTravel'])) {

    $travelID = $_POST['travelID'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $countryID = $_POST['countryID'];

    // Update travel details
    $updateTravel = $connection->prepare("UPDATE travel SET month = ?, year = ?, countryID = ? WHERE id = ?;");
    $updateTravel->bind_param("iiii", $month, $year, $countryID, $travelID);
    $updateTravel->execute();

    // Process each place
    $placeIDs = $_POST['placeID'];
    $placeNames = $_POST['placeName'];
    $locations = $_POST['location'];
    $descriptions = $_POST['description'];
    $newPhotos = $_FILES['newPhoto'];

    for ($i = 0; $i < count($placeIDs); $i++) {
        $placeID = $placeIDs[$i];
        $placeName = $placeNames[$i];
        $location = $locations[$i];
        $description = $descriptions[$i];

        // Check if a new photo is uploaded for this place
        if (isset($newPhotos['name'][$i]) && $newPhotos['error'][$i] == 0) {
            $file_extension = strtolower(pathinfo($newPhotos['name'][$i], PATHINFO_EXTENSION));
            $uniqueFileName = "place_" . $placeID . "." . $file_extension; // Generate filename using placeID
            $target_dir = "uploads/";
            $target_file = $target_dir . $uniqueFileName;

            // Validate file type and move the uploaded file
            if (!in_array($file_extension, ['jpg', 'png', 'jpeg'])) {
                echo "Invalid file type for place $placeID.";
                continue;
            }

            if (move_uploaded_file($newPhotos['tmp_name'][$i], $target_file)) {
                // Update place details including photo
                $updatePlacePhoto = $connection->prepare("UPDATE place SET name = ?, location = ?, description = ?, photoFileName = ? WHERE id = ? AND travelID = ?;");
                $updatePlacePhoto->bind_param("ssssii", $placeName, $location, $description, $uniqueFileName, $placeID, $travelID);
                $updatePlacePhoto->execute();
            } else {
                echo "Error uploading photo for place $placeID.";
                continue;
            }
        } else {
            // Update place details without changing the photo
            $updatePlace = $connection->prepare("UPDATE place SET name = ?, location = ?, description = ? WHERE id = ? AND travelID = ?;");
            $updatePlace->bind_param("sssii", $placeName, $location, $description, $placeID, $travelID);
            $updatePlace->execute();
        }
    }

    header("Location: UserHome.php");
    exit();
}
