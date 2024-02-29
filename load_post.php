<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=ecf_blog;port=3306', 'root', null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) {
    echo json_encode(['error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID de poste non spécifié']);
    exit;
}

$postId = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT body FROM posts WHERE id = :id");
    $stmt->execute(['id' => $postId]);
    $postBody = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM comments WHERE postId = :postId ORDER BY createdAt DESC");
    $stmt->execute(['postId' => $postId]);
    $comments = $stmt->fetchAll();

    $data = [
        'postBody' => $postBody,
        'comments' => $comments
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
} catch (\PDOException $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération du post et des commentaires: ' . $e->getMessage()]);
}
?>
