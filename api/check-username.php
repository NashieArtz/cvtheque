<?php
include '../config/db.php';

$username = $_GET['username'];

$sql = "SELECT id FROM user WHERE `Username` = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$userExists = $stmt->fetch();
if ($userExists) {
    echo json_encode(['taken' => true]);
}
exit;