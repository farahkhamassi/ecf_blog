<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit;
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_post'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $publish_date = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare("INSERT INTO posts (title, content, publish_date) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $publish_date]);
    }
}

$stmt = $pdo->query("SELECT * FROM posts");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Administration</h1>

    <form action="admin.php" method="post">
        <div class="form-group">
            <label for="title">Titre :</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Contenu :</label>
            <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" name="add_post">Ajouter un post</button>
    </form>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Date de publication</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= $post['title'] ?></td>
                    <td><?= $post['content'] ?></td>
                    <td><?= $post['publish_date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
