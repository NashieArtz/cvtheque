<?php
if (isset($_POST) && !empty($_POST)) {
  $username = htmlspecialchars(trim($_POST['username']));
  $pwd = htmlspecialchars(trim($_POST['pwd']));

  $sql = "SELECT * FROM `user` WHERE `username` LIKE '$username'";
  $stmt = $pdo->query($sql)->fetch();
  if (password_verify($pwd, $stmt['password'])) {
    session_start();
    $_SESSION['user'] = [
      'id' => $stmt['id'],
      'username' => $stmt['username'],
      'email' => $stmt['mail'],
      'role_id' => $stmt['role_id'],
    ];
  }
}
