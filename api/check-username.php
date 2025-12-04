<?php
require_once './config/db.php';

header('Content-Type: application/json');

// On récupère le type et valeur
$type = $_GET['type'] ?? '';
$value = $_GET['value'] ?? '';

$response = ['exists' => false];

if ($type && $value) {
    // On prépare la requête selon le type
    if ($type === 'username') {
        $sql = "SELECT id FROM user WHERE username = ?";
    } elseif ($type === 'email') {
        $sql = "SELECT id FROM user WHERE email = ?";
    } else {
        echo json_encode(['error' => 'Invalid type']);
        exit;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$value]);

    // Si on trouve une ligne, ça existe déjà
    if ($stmt->fetch()) {
        $response['exists'] = true;
    }
}

echo json_encode($response);
exit;