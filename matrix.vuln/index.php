<?php
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'default_user_id';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Matrix Museum Identifier</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #333; 
            color: white; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            text-align: center;
        }
        .container {
            width: 300px;
        }
        

        button {
            padding: 10px 20px;
            font-size: 16px;
            width: 100%; 
            cursor: pointer;
            background-color: #555;
            color: #00ff55;
            border: none;
            margin: 20px 0;
            border-radius: 5px;
        }

        button:hover {
            background-color: #777;
        }

        .logo {
            width: 300px; 
            height: 300px; 
            margin: 0 auto 20px; 
        }

        .credentials {
            color: #FFF; 
            font-size: 20px; 
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="Company Logo" class="logo">
        <div>
            <a href="login.php"><button>Page 1 - Login.php - Brute Force and SQLi</button></a>
            <p class="credentials">use bob:bobpassword</p> 
            <a href="reviews.php"><button>Page 2 -  Reviews - XSS</button></a>
            <a href="lfi.php"><button>Page 3 - Sign up for the newsletter! - LFI</button></a>
            <a href="user.php?id=<?php echo $userId; ?>"><button>Page 4 - User Information - IDOR</button></a> 
        </div>
    </div>
</body>
</html>