<?php
include 'edit-travel-details-php.php';
require 'DB_connect.php';
require_once 'check_login.php'; 

if (!isset($_GET['travelID'])) {
    header("Location:user-travel.php");
    exit();
}

$travelID = $_GET['travelID'];

$sql = "SELECT travel.month, travel.year, travel.countryID, 
            place.id AS placeID, place.name AS placeName, 
            place.location, place.description, place.photoFileName
        FROM travel
        JOIN place ON place.travelID = travel.id
        WHERE travel.id = ?;";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $travelID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No places found for this travel.";
    exit();
}

$travelData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
    <head>
        <script src="add.js"></script>
        <title>Update Travel Details</title>
        <style>
            body {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
                background-color: #d1cdc6;
                color: #191a1e;
                padding: 0;
            }

            h1 {
                text-align: center;
                color: #392e27;
                margin-top: 20px;
            }

            form {
                max-width: 1200px;
                margin: 20px auto;
                padding: 20px;
                background: rgba(255, 255, 255, 0.8);
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.183);
                border-radius: 10px;
            }

            label {
                display: block;
                margin-bottom: 10px;
                font-weight: bold;
                color: #191a1e;
            }

            input, select, textarea {
                width: calc(100% - 22px);
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            textarea {
                height: 100px;
                resize: vertical;
            }

            button {
                padding: 10px 20px;
                background-color: #191a1e;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin-right: 10px;
            }

            button:hover {
                background-color: #333;
            }

            fieldset {
                border: 2px solid #191a1e;
                border-radius: 10px;
                padding: 20px;
                background-color: #ffff;
                margin-bottom: 20px;
            }

            legend {
                font-weight: bold;
                color: #191a1e;
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
                <li><a href="UserHome.php">Back to Homepage</a></li>
                <li><a href="log-out.php">Log-out</a></li>
            </ul>
        </nav>


        <form action="edit-travel-details.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="travelID" value="<?php echo $travelID; ?>">
            <label for="month">Month:</label>
            <input type="number" name="month" id="month" value="<?php echo $travelData['month']; ?>" required><br><br>



            <label for="year">Year:</label>
            <input type="number" name="year" id="year" value="<?php echo $travelData['year']; ?>" required><br><br>

            <select name="countryID" id="countryID" required>
                <?php
                $countryQuery = "SELECT id, country FROM country";
                $countryResult = $connection->query($countryQuery);

                while ($country = $countryResult->fetch_assoc()) {
                    $selected = $country['id'] == $travelData['countryID'] ? 'selected' : '';
                    echo "<option value='" . $country['id'] . "' $selected>" . htmlspecialchars($country['country']) . "</option>";
                }
                ?>
            </select>
            <?php
            $placeCount = 1;
            $result->data_seek(0); 
            while ($place = $result->fetch_assoc()) {
                ?>

                <br><br>

                <fieldset>
                    <legend>Place Information<?php echo $placeCount++; ?></legend>

                    <input type="hidden" name="placeID[]" value="<?php echo $place['placeID']; ?>">
                    <ul>

                        <li> <label for="placeName">Place Name:</label> </li>
                        <input type="text" name="placeName[]" value="<?php echo htmlspecialchars($place['placeName']); ?>" required><br><br>

                        <li> <label for="location">Location/City:</label> </li>
                        <input type="text" name="location[]" value="<?php echo htmlspecialchars($place['location']); ?>" required><br><br>

                        <li> <label for="description">Description:</label></li>
                        <textarea name="description[]"><?php echo htmlspecialchars($place['description']); ?></textarea><br><br>

                        <li> <p>Current Photo:</p> </li>
                        <img src="<?php echo 'uploads/'.$place['photoFileName']; ?>" alt="Place Photo" width="100">

                        <label for="newPhoto[]">Upload Photo: (optional):</label>
                        <input type="file" name="newPhoto[]">

                    </ul>
                </fieldset>
                <?php
            }
            ?>


                <button type="submit" name="updateTravel">Update</button>


        </form>

        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section about">
                    <h3>Travel Tales</h3>
                    <p> travellers can share their travel details, <br>and other users can view
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
$connection->close();
?>