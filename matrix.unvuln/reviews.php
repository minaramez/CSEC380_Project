<?php
require_once 'auth_check.php';
$conn = new mysqli("localhost", "root", "", "matrix_museum_notvuln");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'default_user_id';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["fileToUpload"]["name"])) {
    $targetDir = "images/";
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $targetFile = $targetDir . $fileName;

    $maxArtworkIdQuery = "SELECT MAX(artwork_id) AS max_artwork_id FROM art_reviews";
    $maxArtworkIdResult = $conn->query($maxArtworkIdQuery);
    $maxArtworkIdRow = $maxArtworkIdResult->fetch_assoc();
    $artworkId = $maxArtworkIdRow['max_artwork_id'] + 1;

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        $sql = "INSERT INTO artwork_images (artwork_id, file_name) VALUES ('$artworkId', '$fileName')";
        $conn->query($sql);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["comment"])) {
    $userName = $_POST['user_name'] ?? 'Anonymous';
    $artworkId = $_POST['artwork_id'] ?? 0;
    $comment = $_POST['comment'];

    $userName = $conn->real_escape_string($userName);
    $comment = $conn->real_escape_string($comment);
    $artworkId = (int)$artworkId;

    $sql = "INSERT INTO art_reviews (artwork_id, user_name, comment) VALUES ('$artworkId', '$userName', '$comment')";
    if ($conn->query($sql) === TRUE) {
        header("Location: reviews.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<p>Review submitted successfully.</p>";
}

if (isset($_GET['upload']) && $_GET['upload'] == 1) {
    echo "<p>Image uploaded successfully.</p>";
}

?>


<html>
<head>
    <title>Matrix Museum Reviews</title>
    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #333;
        color: white;
        text-align: center; 
        
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #222;
        padding: 20px;
    }
    h1, h2 {
        color: #00ff55;
    }
    
    .logo {
        height: 100px;
        width: 100px
    }
    .form {
        margin: 20px auto;
        width: 300px;
        text-align: center;
    }
    input[type="text"], input[type="submit"], input[type="file"], textarea {
        padding: 8px 16px;
        background-color: #333;
        color: #00ff55;
        border: 1px solid #555;
        padding: 8px 15px;
        cursor: pointer;
        margin-top: 10px;
        font-size: 16px;
        display: block;
        width: 90%; 
        margin: 10px auto; 
    }

    .results-container {
    display: flex;
    flex-direction: column;
    align-items: center; 
    width: 100%; 
    margin-top: 20px; 
    }

    .image-result {
        text-align: center; 
        margin: 20px auto; 
        padding: 20px;
        background-color: #444; 
        border-radius: 8px;
        width: auto; 
    }

    .image-result img {
        width: 350px; 
        height: auto; 
        margin-top: 20px; 
        padding: 0; 
    }


    button {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        background-color: #555;
        color: white;
        border: none;
        border-radius: 5px;
    }
    </style>
</head>
<body>
<header class="header">
        <img src="logo.png" alt="Matrix Museum Logo" class="logo">
        <div class="navigation-buttons">
            <a href="index.php"><button>Go back to directory list</button></a>
            <a href="login.php"><button>Page 1 - Login.php - Brute Force and SQLi</button></a>
            <a href="lfi.php"><button>Page 3 - Sign up for the newsletter! - LFI</button></a>
            <a href="user.php?id=<?php echo $userId; ?>"><button>Page 4 - User Information - IDOR</button></a> 
        </div>
        </header>
    <h1>Artwork Reviews</h1>
    <div class="form">
    <div class="form">
    <form action="reviews.php" method="post" enctype="multipart/form-data">
        Artwork ID: <input type="text" name="artwork_id"><br>
        Your Name: <input type="text" name="user_name"><br>
        Your Comment: <textarea name="comment"></textarea><br>
        <input type="submit" value="Submit Review">
    </form>
    <form action="reviews.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>
    <h2>Uploaded Images:</h2>
    <div class="results-container">
    <?php 
        $imageQuery = "SELECT artwork_id, file_name FROM artwork_images";
        $imageResult = $conn->query($imageQuery);
        while ($imageRow = $imageResult->fetch_assoc()) {
            $artworkId = $imageRow['artwork_id'];
            $fileName = $imageRow['file_name'];
            $imagePath = "images/" . $fileName;

            echo "<div class='image-result'>";
            echo "<img src='" . htmlspecialchars($imagePath) . "' alt='Artwork'>";
            echo "<h2>Artwork ID: " . htmlspecialchars($artworkId) . "</h2>";

            $commentsQuery = "SELECT user_name, comment FROM art_reviews WHERE artwork_id = $artworkId ORDER BY id DESC LIMIT 5";
            $commentsResult = $conn->query($commentsQuery);
            if ($commentsResult->num_rows > 0) {
                while ($commentRow = $commentsResult->fetch_assoc()) {
                    echo "<p><strong>" . htmlspecialchars($commentRow["user_name"]) . ":</strong> " . htmlspecialchars($commentRow["comment"]) . "</p>";
                }
            } else {
                echo "No comments yet.";
            }

            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
