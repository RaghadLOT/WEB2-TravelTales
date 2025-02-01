<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'DB_connect.php';
require_once 'check_login.php';

$userID = $_SESSION['userID'];

$sql = "SELECT firstName, lastName, emailAddress, photoFileName FROM user WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $firstName = $user['firstName'];
    $lastName = $user['lastName'];
    $photoFileName = $user['photoFileName'];
    $email = $user['emailAddress'];

    $photoPath = "uploads/" . $photoFileName;
} else {
    echo "User not found.";
    exit;
}

$countrySql = "SELECT id, country FROM country";
$countryResult = $connection->query($countrySql);
$countries = [];

if ($countryResult->num_rows > 0) {
    while ($row = $countryResult->fetch_assoc()) {
        $countries[] = $row;
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Home Page</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                
                fetchTravels('');

                
                $('#countrySelect').on('change', function () {
                    const countryID = $(this).val();
                    fetchTravels(countryID);
                });

                function fetchTravels(countryID) {
                    $.ajax({
                        url: 'getTravelsByCountry.php',
                        method: 'POST',
                        data: { country_id: countryID },
                        dataType: 'json',
                        success: function (data) {
                            updateTravelsTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }

                function updateTravelsTable(travels) {
                    const tableBody = $('#travelsTableBody');
                    tableBody.empty(); // Clear existing rows

                    if (travels.length === 0) {
                        tableBody.append('<tr><td colspan="4">No travels found.</td></tr>');
                        return;
                    }

                    travels.forEach(travel => {
                        const row = `
                            <tr>
                                <td>
                                    <a href="Travel-details.php?travel_id=${travel.id}">
                                        <img src="uploads/${travel.photoFileName}" alt="Traveler Photo" width="50" height="50">
                                        ${travel.firstName} ${travel.lastName}
                                    </a>
                                </td>
                                <td>${travel.countryName}</td>
                                <td>${travel.month}/${travel.year}</td>
                                <td>${travel.totalLikes}</td>
                            </tr>`;
                        tableBody.append(row);
                    });
                }
            });
        </script>
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

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #4A4F3D;
    padding-bottom: 10px;
}

.header-section h1 {
    margin-top: 10px;
    margin-bottom: 10px;
    font-size: 28px;
    color: #4A4F3D;
}

.auth-links {
    display: flex;
    gap: 15px;
}

.auth-links a {
    text-decoration: none;
    padding: 12px 20px;
    background-color: #4a4f3d; 
    color: white;
    border-radius: 6px;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.auth-links a:hover {
    background-color: #3f4534; 
}

.welcome-note {
    font-size: 18px;
    color: #4a4f3d; 
    margin-bottom: 25px;
}

.user-info {
    display: flex;
    align-items: center;
    background-color: #e9ecef;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.user-info img {
    border-radius: 50%;
    width: 120px;
    height: 120px;
    margin-right: 20px;
    border: 3px solid #f5f5dc; 
}

.user-info h2 {
    margin: 0;
    font-weight: 600;
    color: #4a4f3d; 
}

.user-info p {
    margin: 5px 0;
    color: #6c757d; 
}

.filter-section {
    margin-bottom: 10px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.filter-section select {
    padding: 10px;
    font-size: 16px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background-color: #fff;
    color: #4a4f3d; 
}

.filter-section button {
    padding: 10px 20px;
    background-color: #4A4F3D;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    transition: background-color 0.3s ease;
    align-items: center;
}

.filter-section button:hover {
    background-color: #3f4534; 
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden; 
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.183);
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #4A4F3D;
    color: #fff;
    font-size: 18px;
}

td {
    background-color: #fff;
    color: #4a4f3d; 
}

td img {
    border-radius: 50%;
    width: 45px;
    height: 45px;
}

td a {
    text-decoration: none;
    color: #4a4f3d; 
    font-weight: 600;
}

tbody tr:hover {
    background-color: #e9ecef; 
}

@media (max-width: 768px) {
    .header-section, .user-info {
        flex-direction: column;
        text-align: center;
    }

    .auth-links {
        flex-direction: column;
        gap: 10px;
    }

    .filter-section {
        justify-content: center;
    }

    table, th, td {
        padding: 10px;
    }
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
                <li><a href="user-travel.php">My Travels</a></li>
                <li><a href="log-out.php">Log-out</a></li>
            </ul>
        </nav>

        <div class="container">
            <div class="welcome-note">
                <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
            </div>

            <div class="user-info">
                <img src="<?php echo 'uploads/' . htmlspecialchars($photoFileName); ?>" alt="User Photo" width="100">
                <div>
                    <h2><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></h2>
                    <p>Email: <?php echo htmlspecialchars($email); ?></p>
                </div>
            </div>
        </div>

        <div class="filter-section">
            <label for="countrySelect">Filter by Country:</label>
            <select id="countrySelect">
                <option value="">All Countries</option>
                <?php foreach ($countries as $country): ?>
                    <option value="<?php echo $country['id']; ?>">
                        <?php echo htmlspecialchars($country['country']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Traveller</th>
                    <th>Country</th>
                    <th>Month/Year</th>
                    <th>Total Likes</th>
                </tr>
            </thead>
            <tbody id="travelsTableBody">
                
            </tbody>
        </table>
    </body>
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
</html>
