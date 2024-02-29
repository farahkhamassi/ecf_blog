<?php
$title = "Login"; 
require 'config.php';

$error = null;
$success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {

    $mysqli = new mysqli("localhost", "root", "", "ecf_blog", 3306);
    if ($mysqli->connect_error) {
        die("Échec de la connexion : " . $mysqli->connect_error);
    }

    $username = $mysqli->real_escape_string($_POST['username']); 
    $password = $mysqli->real_escape_string($_POST['password']); 

    $query = "SELECT * FROM user WHERE username='$username'";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) { 
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header('Location: admin.php');
                exit;
            } elseif ($user['role'] == 'user') {
                header('Location: index.php');
                exit;
            }
        } else {
            $error = 'Mauvais identifiants';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Connexion</h2>

    <?php if (!empty($error)): ?>
        <p><?= $error ?></p>
    <?php endif; ?>

    <form action="login.php" method="post">
        <div>
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username" autocomplete="username">
        </div>

        <div>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" autocomplete="current-password">
        </div>

        <div>
            <input type="submit" value="Se connecter">
        </div>
    </form></div>

</body>
</html>
