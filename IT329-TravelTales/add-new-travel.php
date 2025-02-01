<?php 
session_start(); 
require_once 'DB_connect.php'; 
require_once 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
     if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID']; 
    } else {
        die("User is not logged in."); 
    }

    $month = mysqli_real_escape_string($connection, $_POST['month']);
    $year = mysqli_real_escape_string($connection, $_POST['year']);
    $countryID = mysqli_real_escape_string($connection, $_POST['countryID']);

    $sql = "INSERT INTO travel (userID, month, year, countryID) VALUES ('$userID','$month', '$year', '$countryID');";
    if (mysqli_query($connection, $sql)) {
        $travelID = mysqli_insert_id($connection);
        header("Location: add-vistied-place.php?travelID=$travelID");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="tab-logo.png">
    <title>Add New Travel</title>
    <style>
            body {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
                background-color: #d1cdc6;
                color: #4A4F3D;
                margin: 0;
                padding-left: 15px;
                padding-right: 15px
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
                border-radius: 10px;           
            }

            label {
                display: block;
                margin-bottom: 10px;
                font-weight: bold;
                color: #4A4F3D;
            }

            select, button {
                width: 45%;
                padding: 10px;
                margin-bottom: 20px;
                margin-left: 30px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }
            
            #month {
                margin-right: 15px;
            }

            button {
                width: 400px;
                height: 45px;
                background-color:#4A4F3D;
                color: white;
                cursor: pointer;
                border-radius: 50px;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: #333;
            }

            h2 {
                margin-top: 0;
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

    <form id="travelForm" action="add-new-travel.php" method="POST">
        <h1>New Travel</h1>
        
        <label for="month">Travel Time:</label>
        <select id="month" name="month" required>
            <option value="" disabled selected>Select Month</option>
            <?php for ($m = 1; $m <= 12; $m++) : ?>
                <option value="<?php echo $m; ?>"><?php echo date("F", mktime(0, 0, 0, $m, 10)); ?></option>
            <?php endfor; ?>
        </select>

        <select id="year" name="year" required>
            <option value="" disabled selected>Select Year</option>
            <?php for ($y = 2000; $y <= date("Y"); $y++) : ?>
                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
            <?php endfor; ?>
        </select>

        <label for="TravCountry">Country:</label>
        <select name="countryID" id="TravCountry" required>
            <option value="" disabled selected>Select your country</option>
            <?php
            $countries = [
                "1" => "USA", "2" => "France", "3" => "Ireland", "4" => "Japan",
                "5" => "London", "6" => "Brazil"];// 

            foreach ($countries as $id => $name) {
                echo "<option value=\"$id\">$name</option>";
            }
            ?>
        </select>

        <button type="submit" name="submit">Next</button>
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

if (isset($connection)) {
    mysqli_close($connection);
}
?>
