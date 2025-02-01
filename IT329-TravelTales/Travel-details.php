<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'DB_connect.php';
require_once 'check_login.php';

$userID = $_SESSION['userID'];

if (!isset($_GET['travel_id'])) {
    die("Travel ID is missing.");
}

$travelID = $_GET['travel_id'];

$sql = "SELECT travel.id, travel.month, travel.year, country.country AS countryName, user.firstName, user.lastName, user.photoFileName
        FROM travel 
        JOIN country ON travel.countryID = country.id 
        JOIN user ON travel.userID = user.id 
        WHERE travel.id = ?;";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $travelID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $travel = $result->fetch_assoc();
} else {
    die("No travel details found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_place_id'])) {
    $placeID = $_POST['like_place_id'];

    $likeSql = "INSERT INTO likee (userID, placeID) VALUES (?, ?)";
    $likeStmt = $connection->prepare($likeSql);
    $likeStmt->bind_param('ii', $userID, $placeID);
    $likeStmt->execute();
    $likeStmt->close();

    header("Location: Travel-details.php?travel_id=" . $travelID);
    exit();
}

$placesSql = "SELECT place.id AS placeID, place.name, place.photoFileName,
              (SELECT COUNT(*) FROM likee WHERE placeID = place.id) AS totalLikes,
              (SELECT COUNT(*) FROM likee WHERE userID = ? AND placeID = place.id) AS userLikes
              FROM place 
              WHERE travelID = ?";
$placesStmt = $connection->prepare($placesSql);
$placesStmt->bind_param('ii', $userID, $travelID);
$placesStmt->execute();
$placesResult = $placesStmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $commentText = $_POST['comment_text'];
    $placeID = $_POST['place_id'];

    $commentSql = "INSERT INTO comment (userID, placeID, comment) VALUES (?, ?, ?)";
    $commentStmt = $connection->prepare($commentSql);
    $commentStmt->bind_param('iis', $userID, $placeID, $commentText);
    $commentStmt->execute();
    $commentStmt->close();

    header("Location: Travel-details.php?travel_id=" . $travelID);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Travel Details</title>
        <style>
            body {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
                background-color: #d1cdc6;
                margin: 0;
                padding: 20px;
            }
            .travel-details-container {
                max-width: 900px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                border-top: 5px solid #4A4F3D;
            }
            h1 {
                text-align: center;
                font-size: 28px;
                color: #00203F;
                margin-bottom: 30px;
            }
            .place {
                background-color: #fefefe;
                border: 1px solid #00203F;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 20px;
                position: relative;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .place-image {
                width: 100%;
                height: auto;
                max-height: 300px;
                object-fit: cover;
                border-radius: 8px;
                margin-bottom: 10px;
            }
            .like-button {
                position: absolute;
                top: 15px;
                right: 15px;
                background-color: #4A4F3D;
                color: white;
                border: none;
                font-size: 18px;
                cursor: pointer;
                padding: 8px 12px;
                border-radius: 20px;
            }
            .comments-section {
                margin-top: 10px;
            }
            .comment {
                background-color: #E5E5E5;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 10px;
            }
            .comment-form input[type="text"] {
                width: 80%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
                margin-right: 10px;
            }
            .comment-form input[type="submit"] {
                padding: 10px;
                background-color: #4A4F3D;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
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
            .travel-details-container {
                max-width: 1200px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                font-size: 28px;
                color: #392e27;
                margin-bottom: 30px;
            }

            .traveler-info {

                padding: 15px;
                border-radius: 8px;
                margin-bottom: 20px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            }

            .traveler-photo-name {
                display: flex;
                align-items: center;
            }

            .traveler-photo {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                object-fit: cover;
                margin-right: 15px;
                border: 3px solid #00203F;
            }

            .traveler-name {
                font-size: 18px;
                color: #4A4F3D;
            }


        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                
                $('.like-button').click(function () {
                    var placeID = $(this).closest('form').find('input[name="like_place_id"]').val();
                    var likeButton = $(this);
                    likeButton.prop('disabled', true); 

                    $.ajax({
                        url: 'like_place.php',
                        type: 'POST',
                        data: {placeID: placeID},
                        dataType: 'json',
                        success: function (data) {
                            if (data.success) {
                                likeButton.text('Liked (' + data.totalLikes + ')');
                            } else {
                                alert(data.message);
                                likeButton.prop('disabled', false);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert('An error occurred: ' + textStatus + ' - ' + errorThrown);
                            likeButton.prop('disabled', false);
                        }
                    });
                });

                
                window.addComment = function (placeID) {
                    const commentText = $(`#comment-text-${placeID}`).val().trim();

                    if (commentText === "") {
                        alert("Comment cannot be empty!");
                        return;
                    }

                    $.ajax({
                        url: 'add_comment.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            place_id: placeID,
                            comment_text: commentText
                        },
                        success: function (data) {
                            if (data.success) {
                                const commentsContainer = $(`#comments-container-${placeID}`);
                                const newComment = $(
                                        `<div class="comment"><strong>${data.data.userName}:</strong> ${data.data.comment}</div>`
                                        );
                                commentsContainer.append(newComment); 
                                $(`#comment-text-${placeID}`).val(''); 
                            } else {
                                alert(data.message || "Failed to add comment.");
                            }
                        },
                        error: function () {
                            alert("An error occurred while connecting to the server.");
                        }
                    });
                };
            });
        </script>
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
        <div class="travel-details-container">
            <h1>Travel Details for <?php echo htmlspecialchars($travel['countryName']); ?></h1>

            <div class="traveler-info">
                <p><strong>Traveller:</strong></p>
                <div class="traveler-photo-name">
                    <img src="<?php echo 'uploads/' . htmlspecialchars($travel['photoFileName']); ?>" alt="Traveler Photo" class="traveler-photo">
                    <p class="traveler-name"><?php echo htmlspecialchars($travel['firstName'] . ' ' . $travel['lastName']); ?></p>
                </div>
                <p><strong>Travel to</strong> <span id="country"><?php echo htmlspecialchars($travel['countryName']); ?></span>, in 
                    <span id="time"><?php echo htmlspecialchars($travel['month'] . '/' . $travel['year']); ?></span>:</p>
            </div>

            <h3>Places:</h3>
            <?php while ($place = $placesResult->fetch_assoc()): ?>
                <div class="place">
                    <h4><?php echo htmlspecialchars($place['name']); ?></h4>

                    <div class="polaroid">
                        <?php if (!empty($place['photoFileName'])) { ?>

                            <img src="<?php echo 'uploads/' . htmlspecialchars($place['photoFileName']); ?>" alt="Place Photo" width="100%" class="place-image">
                        <?php } else { ?>
                            <p>No image available</p>
                        <?php } ?>
                        <div class="container">
                            <p><?php echo htmlspecialchars($place['name']); ?></p>
                        </div>
                    </div>

                    <form method="POST" action="" class="like-form">
                        <input type="hidden" name="like_place_id" value="<?php echo $place['placeID']; ?>">
                        <button type="button" class="like-button" data-place-id="<?php echo $place['placeID']; ?>" 
                                <?php echo ($place['userLikes'] > 0) ? 'disabled' : ''; ?>>
                            Like (<?php echo htmlspecialchars($place['totalLikes']); ?>)
                        </button>
                    </form>

                    <form method="POST" action="" class="like-form">
                        <input type="hidden" name="like_place_id" value="<?php echo $place['placeID']; ?>">
                        <button type="button" class="like-button" 
                                <?php echo ($place['userLikes'] > 0) ? 'disabled' : ''; ?>>
                            Like (<?php echo htmlspecialchars($place['totalLikes']); ?>)
                        </button>
                    </form>

                    <div id="comments-container-<?php echo $place['placeID']; ?>">
                        <h4>Comments:</h4>
                        <?php
                        $commentsSql = "SELECT comment.comment, user.firstName
                                    FROM comment 
                                    JOIN user ON comment.userID = user.id
                                    WHERE comment.placeID = ?";
                        $commentsStmt = $connection->prepare($commentsSql);
                        $commentsStmt->bind_param('i', $place['placeID']);
                        $commentsStmt->execute();
                        $commentsResult = $commentsStmt->get_result();

                        while ($comment = $commentsResult->fetch_assoc()):
                            ?>
                            <div class="comment">
                                <strong><?php echo htmlspecialchars($comment['firstName']); ?>:</strong>
                            <?php echo htmlspecialchars($comment['comment']); ?>
                            </div>
    <?php endwhile; ?>
                    </div>
                    <div class="comment-form">
                        <input type="text" id="comment-text-<?php echo $place['placeID']; ?>" placeholder="Add a comment" required>
                        <button onclick="addComment(<?php echo $place['placeID']; ?>)">Add Comment</button>
                    </div>
                </div>
<?php endwhile; ?>
        </div>
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