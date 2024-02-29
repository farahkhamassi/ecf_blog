<?php
$title = "Login"; 
require 'config.php';

$error = null;
$success = null;

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit;
}

$perPage = 12;

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT posts.*, user.name AS author_name
    FROM posts
    INNER JOIN user ON posts.userId = user.id
    ORDER BY posts.createdAt DESC
    LIMIT :perPage OFFSET :offset
");
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

$totalPostsStmt = $pdo->query("SELECT COUNT(*) FROM posts");
$totalPosts = $totalPostsStmt->fetchColumn();

$totalPages = ceil($totalPosts / $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="app.js" defer></script>
    <title>ECF 3 - Blog</title>
</head>
<body class="container mt-5">
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
    <h1>Voici les derniers articles du blog</h1>
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Publié le <?= htmlspecialchars($post['createdAt']) ?></h6>
                        <p class="card-text"><?= htmlspecialchars($post['body']) ?></p>
                        <p class="card-text"><strong>Auteur:</strong> <?= htmlspecialchars($post['author_name']) ?></p>
                        <button class="btn btn-primary load-post-btn" data-post-id="<?= $post['id'] ?>">Lire la suite du post</button>
                        <div class="comments-container" style="display: none;"></div>
                        <div class="post-container"></div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?= ($page == 1 ? 'disabled' : '') ?>">
                <a class="page-link" href="?page=<?= ($page - 1) ?>">Précédent</a>
            </li>
            <li class="page-item <?= ($page == $totalPages ? 'disabled' : '') ?>">
                <a class="page-link" href="?page=<?= ($page + 1) ?>">Suivant</a>
            </li>
        </ul>
    </nav>
</body>
</html>
