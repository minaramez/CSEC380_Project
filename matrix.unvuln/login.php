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
        .navigation-buttons {
            width: 60%; 
            margin-top: 50px; 
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
        .navigation-buttons {
            display: flex;
            justify-content: center; 
            align-items: center;     
            height: 100px;           
            margin: 0 auto;          
            width: 100%;             
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


    </style>
</head>
<body>

    <header class="header">
        <img src="logo.png" alt="Matrix Museum Logo" class="logo">
    </header>
    <div class="navigation-buttons">
            <a href="index.php"><button>Go back to directory list</button></a>
        </div>
    <div class="form">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <input type="submit" value="Login">
        </form>
        <?php
        ini_set('display_errors', 0);
        session_start(); 

        function check_rate_limit($ip, $conn) {
            $limit = 5;
            $timeout = 300; 

            $stmt = $conn->prepare("SELECT attempts, last_attempt FROM rate_limit WHERE ip_address = ?");
            $stmt->bind_param("s", $ip);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                if (time() - $row['last_attempt'] > $timeout) {
                    $stmt = $conn->prepare("UPDATE rate_limit SET attempts = 1, last_attempt = ? WHERE ip_address = ?");
                    $stmt->bind_param("is", time(), $ip);
                    $stmt->execute();
                    return true;
                } elseif ($row['attempts'] < $limit) {
                    $stmt = $conn->prepare("UPDATE rate_limit SET attempts = attempts + 1 WHERE ip_address = ?");
                    $stmt->bind_param("s", $ip);
                    $stmt->execute();
                    return true;
                } else {
                    return false;
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO rate_limit (ip_address, attempts, last_attempt) VALUES (?, 1, ?)");
                $stmt->bind_param("si", $ip, time());
                $stmt->execute();
                return true;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = new mysqli("localhost", "root", "", "matrix_museum_notvuln");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $ip = $_SERVER['REMOTE_ADDR'];
            if (!check_rate_limit($ip, $conn)) {
                echo "<p>Too many failed login attempts. Please try again later.</p>";
            } else {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                $result = $stmt->get_result();
                
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
        }
        ?>
    </div>
</body>
</html>
