<!DOCTYPE html>
<html>
<head>
    <title>Matrix Museum Login</title>
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
        text-align: center;
    }
    
    .logo {
        display: block; 
        margin: 0 auto; 
        width: 300px;
        height: 300px; 
    }
    .form {
        margin: 40px auto;
        width: 300px;
        text-align: center;
    }
    input[type="text"], input[type="password"], input[type="submit"] {
        padding: 8px 16px;
        margin-top: 10px;
        font-size: 16px;
        display: block;
        width: 90%; 
        margin: 10px auto; 
    }
    .navigation-buttons a {
        text-decoration: none;
    }

    .button {
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
    </style>

</head>
<body>
<header class="header">
    <img src="logo.png" alt="Matrix Museum Logo" class="logo">
    <div class="navigation-buttons">
            <a href="index.php"><button>Go back to directory list</button></a>
    </div>

</header>
<div class="form">
    <h1>Login</h1>
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Username"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <input type="submit" value="Login">
    </form>
    <?php
    @session_start(); 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $conn = new mysqli("localhost", "root", "", "matrix_museum");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        ini_set('display_errors', 0);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['logged_in'] = true; 
            $_SESSION['username'] = $username; 
            $_SESSION['user_id'] = $user['id']; 
            header('Location: user.php?id=' . $user['id']); 
            exit(); 
        } else {
            echo "<p>Incorrect username or password.</p>";
        }
    }
    ?>
</div>
</body>
</html>
