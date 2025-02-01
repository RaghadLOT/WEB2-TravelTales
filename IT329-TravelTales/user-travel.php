<?php
require_once 'DB_connect.php';
include_once 'user-travel-php.php';
include_once 'check_login.php';
//include_once 'delete-travel.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($first_name); ?>'s Travel Page</title>
        <link rel="icon" href="tab-logo.png" type="image/png">
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

            .container {
                max-width: 1200px;
                margin: 20px auto;
                padding: 20px;
                background: rgba(255, 255, 255, 0.8);
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.183);
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #4A4F3D;
                padding-bottom: 10px;
            }

            .header h1 {
                margin-top: 10px;
                margin-bottom: 10px;
                font-size: 28px;
                color: #4A4F3D;
            }

            .travels-section {
                margin-top: 20px;
            }

            .travels-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }

            .add-travel-link {
                text-decoration: none;
                color: #882D17;
                font-weight: bold;
                background-color: #d1cdc6;
                padding: 5px 10px;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .add-travel-link:hover {
                background-color: #4A4F3D;
                color: #fff;
            }

            .travels-table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0; 
                border-radius: 10px; 
                overflow: hidden; 
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.183);
            }

            .travels-table, .travels-table th, .travels-table td {
                border: 1px solid #ddd;
            }

            .travels-table th {
                padding: 12px;
                text-align: left;
                background-color: #4A4F3D;
                color: #fff;
            }

            .travels-table td {
                padding: 10px;
                background-color: #f9f9f9;
            }

            .travels-table td a {
                color: #4A4F3D;
                text-decoration: none;
            }

            .travels-table td a:hover {
                color: #e74c3c;
            }

            .travel-photo {
                width: 100px;
                height: auto;
                border-radius: 8px;
            }

            .like-icon {
                margin-right: 5px;
                color: #e74c3c;
            }

            .comments-section {
                font-size: 12px;
                color: #555;
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

        <div class="container">
            <!-- Header -->
            <header class="header">
                <h1><?php echo htmlspecialchars($first_name); ?>'s Travels</h1>
            </header>

            <!-- Travels Section -->
            <section class="travels-section">
            <div class="travels-header">
                <h2>All Travels</h2>
                <a href="add-new-travel.php" id="add-new-travel-link" class="add-travel-link">Add New Travel</a>
            </div>

            <table class="travels-table">
                <thead>
                    <tr>
                        <th rowspan="2">Travel</th>
                        <th rowspan="2">Travel Time</th>
                        <th rowspan="2">Country</th>
                        <th colspan="6">Places</th>
                    </tr>
                    <tr>
                        <th>Place Name</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Photo</th>
                        <th>Likes</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                  <tbody id="travels-table-body">
                        <?php
                        $travels_data = [];
                        while ($row = mysqli_fetch_assoc($travels_result)) {
                            $travels_data[$row['travel_id']][] = $row;
                        }

                        foreach ($travels_data as $travel_id => $places) {
                            $rowspan = count($places);
                            $first_place = $places[0];
                            echo "<tr>";
                            echo "<td rowspan='{$rowspan}'>{$travel_id}<br><br>
                                <a href='edit-travel-details.php?travelID={$travel_id}' class='edit-travel-link'>Edit</a><br>
                                <a href='delete-travel.php?travelID={$travel_id}' class='delete-travel-link' onclick=\"return confirm('Are you sure you want to delete this travel?');\">Delete</a></td>";
                            echo "<td rowspan='{$rowspan}'>{$first_place['month']}/{$first_place['year']}</td>";
                            echo "<td rowspan='{$rowspan}'>{$first_place['country']}</td>";
                            echo "<td>{$first_place['place_name']}</td>";
                            echo "<td>{$first_place['location']}</td>";
                            echo "<td>{$first_place['description']}</td>";
                            echo "<td><img src='uploads/{$first_place['photoFileName']}' alt='uploads/{$first_place['place_name']}' class='travel-photo'></td>";
                            echo "<td>❤️ {$first_place['likes_count']}</td>";
                            echo "<td>{$first_place['comments']}</td>";
                            echo "</tr>";

                            for ($i = 1; $i < $rowspan; $i++) {
                                $place = $places[$i];
                                echo "<tr>";
                                echo "<td>{$place['place_name']}</td>";
                                echo "<td>{$place['location']}</td>";
                                echo "<td>{$place['description']}</td>";
                                echo "<td><img src='uploads/{$place['photoFileName']}' alt='uploads/{$place['place_name']}' class='travel-photo'></td>";
                                echo "<td>❤️ {$place['likes_count']}</td>";
                                echo "<td>{$place['comments']}</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
            </table>
        </section>
        </div>

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
         <script>
function showSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.style.display = 'flex';
}

function hideSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.style.display = 'none';
}


function deleteTravel(travelID, deleteLink) {
    if (confirm("Are you sure you want to delete this travel?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "delete-travel.php?travelID=" + travelID, true);
        xhr.setRequestHeader("Content-type", "application/json");

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        
                        const travelRow = deleteLink.closest("tr");
                        if (travelRow) {
                            travelRow.remove();
                        }
                    } else {
                        alert(response.message || "Failed to delete travel.");
                    }
                } catch (error) {
                    alert("Error in response: " + error.message);
                }
            }
        };
        xhr.send();
    }
}



</script>

    </body>
</html>