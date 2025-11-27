<?php

if (isset($_POST) && !empty($_POST)) {
  $username = htmlspecialchars(trim($_POST['username']));
  $pwd = htmlspecialchars(trim($_POST['pwd']));

  $sql = "SELECT * FROM `user` WHERE `username` LIKE '$username'";
  $stmt = $pdo->query($sql)->fetch();
  if (password_verify($pwd, $stmt['pwd'])) {

    $_SESSION['user'] = [
      'id' => $stmt['id'],
      'username' => $stmt['username'],
      'email' => $stmt['email'],
      'role_id' => $stmt['role_id']
    ];
    session_start();
    header('Location: ./index.php');
  }
}

?>
<link href="./assets/css/register-login.css" rel="stylesheet">
<form method="post">
  <label for="username">Nom d'utilisateur</label>
  <input type="text" name="username" id="username">
  <label for="pwd">Mots de passe</label>
  <input type="password" name="pwd" id="pwd">
  <input type="submit" name="button" id="button">
  <button class="btn btn-secondary btn-lg w-100" type="submit">
    <a href="?page=dashboard" class="text-white text-decoration-none d-block">Accueil</a>
  </button>
</form>
