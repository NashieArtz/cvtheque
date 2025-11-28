<?php

$DB_DSN = 'mysql:host=127.0.0.1;dbname=crunchy;charset=utf8mb4';
$DB_USER = 'root';
$DB_PASS = '';
try {
  $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
  var_dump('PDO CONNECTED', $pdo);
} catch (Throwable $error) {
  http_response_code(500);
  echo "ERREUR BDD : " . $error->getMessage();
  exit;
}
if (isset($_POST) && !empty($_POST)) {
  $name = htmlspecialchars(trim($_POST['name']));
  $category = htmlspecialchars(trim($_POST['category']));
  $date = htmlspecialchars(trim($_POST['date']));

  $sql = "INSERT INTO `Anime` (`name`, `category`, `release`) VALUES (?,?,?);";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$name, $category, $date]);
  echo "Animé crée : " . $pdo->lastInsertId();
}
