<?php 
session_start(); 
ini_set('display_errors','1');
require_once 'DB_connect.php'; 
require_once 'check_login.php';

if (!isset($_SESSION['userID'])) {
    die("User is not logged in.");
}

$userID = $_SESSION['userID']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {   
    $placeName = mysqli_real_escape_string($connection, $_POST['placeName']);
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $travelID = mysqli_real_escape_string($connection, $_POST['travelID']);
    
    // Default photo path in case of no upload
    $photoPath = "null.png";
    
    $inserplace = $connection->prepare("INSERT INTO place (travelID, name, location, description, photoFileName) VALUES (?, ?, ?, ?, ?);");
    $inserplace->bind_param("issss", $travelID, $placeName, $location, $description, $photoPath);

    if ($inserplace->execute()) {
        $placeID = $connection->insert_id; // Get the inserted placeID
        
        // Check if a photo was uploaded
        if (!empty($_FILES['photo']['name'])) {
            $target_dir = "uploads/";
            $file_extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $uniqueFileName = "place_" . $placeID . "." . $file_extension; // Using placeID in filename
            $target_file = $target_dir . $uniqueFileName;

            // Validate the uploaded file
            if (getimagesize($_FILES['photo']['tmp_name']) === false) {
                header("Location:add-vistied-place.php?error=invalidfile");
                exit();
            }

            if (!in_array($file_extension, ['jpg', 'png', 'jpeg'])) {
                header("Location:add-vistied-place.php?error=invalidfiletype");
                exit();
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                $photoPath = $uniqueFileName; // Update the photoPath to the new file
            } else {
                header("Location:add-vistied-place.php?error=uploadfail");
                exit();
            }
        }

        // Update the photoPath in the database
        $updatePhoto = $connection->prepare("UPDATE place SET photoFileName = ? WHERE id = ?");
        $updatePhoto->bind_param("si", $photoPath, $placeID);
        $updatePhoto->execute();

        // Redirect based on the submitted button
        if ($_POST['Submit'] == 'Add Another Place') {
            header("Location: add-vistied-place.php?travelID=" . $travelID);
            exit();
        } elseif ($_POST['Submit'] == 'Done') {
            header("Location: UserHome.php?userID=" . $userID);
            exit();
        }
    } else {
        echo "Error adding place: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="tab-logo.png">
    <title>Add Visited Place</title>
    <style>
        body {
    font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    margin: 0;
    padding-left: 15px;
    padding-right: 15px;
    background-color: #d1cdc6;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 20px auto;
    margin-top: -10px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.183);
}

.logo img {
    height: 100px;
}

.nav-links {
    font-size: 20px;
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
}

.nav-links li {
    padding: 0 15px;
}

.nav-links a {
    margin-left: 20px;
    text-decoration: none;
    color: #4A4F3D;
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav-links a:hover {
    color: #882D17;
    text-decoration: underline;
}

.footer {
    background-color: #333;
    color: #4A4F3D;
    text-align: center;
    padding: 20px;
    max-width: 1200px;
    margin: 15px auto;
    margin-bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.183);
}

.footer-content {
    display: flex;
    justify-content:space-evenly;
    align-items: top;
    padding: 20px;
}

.footer-section {
    margin: 10px;
}

.footer-section h3 {
    margin-bottom: 10px;
}

.footer-section ul {
    list-style-type: none;
    padding: 0;
}

.footer-section ul li a {
    color: #4A4F3D;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-section ul li a:hover {
    color:#882D17;
    text-decoration: underline;
}

.footer-section a {
    color:#4A4F3D;
    text-decoration: none;
}

.footer-section a:hover {
    color:#882D17;
    text-decoration: underline;
}

.footer-bottom {
    margin-top: 20px;
    margin-bottom: 20px;
    font-size: 0.8em;

        }

        h1 {
            margin-top: 20px;
            text-align: center;
            color: #392e27;
        }

        form {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.183);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #4A4F3D;
        }

        input[type="text"], textarea, input[type="file"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button, input[type="submit"] {
            width: 100%;
            height: 45px;
            background-color: #4A4F3D;
            color: white;
            cursor: pointer;
            border-radius: 50px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        button:hover, input[type="submit"]:hover {
            background-color: #333;
        }

        
    </style>
</head>
<body>
    <nav class="navbar">
    <div class="logo">
        <a href="https://traveltales.infinityfreeapp.com/TravelTales/HomePage.php">
            <img src="logo-traveltales.png" alt="Logo">
        </a>
    </div>
            <ul class="nav-links">
                <li><a href="user-travel.php?userID=<?php echo $userID; ?>">My Travels</a></li>
                <li><a href="log-out.php">Log-out</a></li>
            </ul>
        </nav>

    <form id="visitedPlaceForm" action="add-vistied-place.php" method="POST" enctype="multipart/form-data">
        <h1>Add Visited Place</h1>
        <input type="hidden" name="travelID" value="<?php echo htmlspecialchars($_GET['travelID'] ?? ''); ?>">

        <label for="placeName">Place Name:</label>
        <input type="text" id="placeName" name="placeName" required>

        <label for="location">Location/city:</label>
        <input type="text" id="location" name="location" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>
        
        <label for="photo">Upload Photo:</label>
        <input type="file" id="photo" name="photo" required>
        
        <button type="submit" name="Submit" value="Add Another Place">Add Another Place</button>
        <button type="submit" name="Submit" value="Done">Done</button>

    </form>

    <footer class="footer">
            <div class="footer-content">
                <div class="footer-section about">
                    <h3>Travel Tales</h3>
                    <p> Travellers can share their travel details, <br>and other users can view
                        them, <br>like them, and add comments.</p>
                </div>
                <div class="footer-section links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                    </ul>
                </div>
                <div class="footer-section social">
                    <h3>Follow Us</h3>
                    <a href="#">Facebook</a>
                    <a href="#">Twitter</a>
                    <a href="#">Instagram</a>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2024 Company Name | Designed by TravelTales
            </div>
        </footer>
</body>
</html>

<?php

if (isset($connection)) {
    mysqli_close($connection);
}
?>
