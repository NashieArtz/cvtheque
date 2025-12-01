<?php
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Prépare et exécute la requête de suppression
    $stmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
    $stmt->execute(['id' => $userId]);
}

// Redirection vers le dashboard admin
header("Location: admin-dashboard");
exit;
