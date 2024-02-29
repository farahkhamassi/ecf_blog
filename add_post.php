<?php
$title = "Ajouter un Poste"; 
require 'config.php';

$error = null;
$success = null;

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $publish_date = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO posts (title, content, publish_date) VALUES (?, ?, ?)");
    $stmt->execute([$title, $content, $publish_date]);
    $success = "Le poste a été ajouté avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Poste</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Blog</a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="edit_post.php">Administration</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if ($logoutUrl): ?>
                    <a href="<?= $logoutUrl ?>" class="btn btn-danger">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
<div class="container mt-5">
    <h1>Ajouter un Poste</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success" role="alert">
            <?= $success ?>
        </div>
    <?php endif; ?>
    <form action="add_post.php" method="post">
        <div class="form-group">
            <label for="title">Titre :</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Contenu :</label>
            <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" name="add_post">Ajouter</button>
    </form>
</div>
</body>
</html>
