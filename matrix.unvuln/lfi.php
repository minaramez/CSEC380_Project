<?php
require_once 'auth_check.php';
$conn = new mysqli("localhost", "root", "", "matrix_museum_notvuln");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'] ?? 'default_user_id';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subscribe to Our Newsletter</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #333;
            color: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #222;
            padding: 20px;
        }
        .logo {
            height: 100px;
            width: 100px;
        }
        .form {
            margin: 40px auto;
            width: 300px;
            text-align: center;
        }
        input[type="text"], input[type="submit"] {
            padding: 8px 16px;
            margin-top: 10px;
            font-size: 16px;
        }
        .navigation-buttons a {
            text-decoration: none;
        }
        button {
            padding: 8px 16px;
            margin-left: 10px;
            font-size: 16px;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #777;
        }
        h1, h2 {
            color: #00ff55;
        }
        .main-content {
            width: 100%;
            text-align: center;
            color: #00ff55;
        }
    </style>
</head>
<body>
    <header class="header">
        <img src="logo.png" alt="Matrix Museum Logo" class="logo">
        <div class="navigation-buttons">
            <a href="index.php"><button>Go back to directory list</button></a>
            <a href="login.php"><button>Page 1 - Login.php - Brute Force and SQLi</button></a>
            <a href="reviews.php"><button>Page 2 - Reviews - XSS</button></a>
            <a href="user.php?id=<?php echo $userId; ?>"><button>Page 4 - User Information - IDOR</button></a>
        </div>
    </header>
    <div class="form">
        <h1>Sign up for our newsletter!</h1>
        <form method="post">
            <input type="text" name="email" placeholder="Enter your email" autofocus>
            <input type="submit" value="Subscribe">
        </form>
    </div>
    <div class="main-content">
        <?php
        if (isset($_POST['email'])) {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Thank you for subscribing to our newsletter!";
            } else {
                echo "Invalid email address.";
            }
        }
        ?>
    </div>
</body>
</html>
