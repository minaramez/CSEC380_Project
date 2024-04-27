<!DOCTYPE html>
<html>
<head>
    <title>View User Account</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #333;
            color: white;
            text-align: center;
        }

        table {
            margin: 20px auto;
            width: 60%;
            border-collapse: collapse; 
            background-color: #222;
            color: #fff;
        }

        th, td {
            padding: 12px 20px;
            border-bottom: 1px solid #555; 
        }

        th {
            background-color: #444; 
        }

        td {
            background-color: #333; 
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
            width: 100px;
        }
        .navigation-buttons button {
            padding: 10px 20px;
            background-color: #555;
            border: none;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
        .navigation-buttons button:hover {
            background-color: #777;
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
        <a href="lfi.php"><button>Page 3 - Sign up for the newsletter! - LFI</button></a>
    </div>
</header>
<div class="content">
    <h1>User Account Details</h1>
</body>
</html>

<?php
require_once 'auth_check.php'; 
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php'); 
    exit();
}

$conn = new mysqli("localhost", "root", "", "matrix_museum_notvuln");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $requested_id = $_GET['id'];
    $user_id = $_SESSION['user_id']; 
    $is_admin = $_SESSION['is_admin'] ?? false; 


    if (!$is_admin && $requested_id != $user_id) {
        echo "You do not have permission to view this page.";
        exit;
    }


    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $requested_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<table>";
            echo "<tr><th>Username</th><td>" . htmlspecialchars($row["username"]) . "</td></tr>";
            echo "<tr><th>Email</th><td>" . htmlspecialchars($row["email"]) . "</td></tr>";
            echo "<tr><th>Full Name</th><td>" . htmlspecialchars($row["fullname"]) . "</td></tr>";
            echo "</table><br>";
        }
    } else {
        echo "No user found.";
    }
} else {
    echo "No user ID provided.";
}
?>


