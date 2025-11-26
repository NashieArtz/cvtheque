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
      'email' => $stmt['email'],
      'role_id' => $stmt['role_id']
    ];
    header('Location: http://locahost/');
  }
}

?>
<link href="./assets/css/register-login.css" rel="stylesheet">
<form method="post">
    <label for="username">Nom d'utilisateur</label>
    <input type="text" name="username" id="username">
    <label for="password">Mots de passe</label>
    <input type="password" name="password" id="password">
    <input type="submit" name="button" id="button">
    <button class="btn btn-secondary btn-lg w-100" type="button">
        <a href="?page=dashboard" class="text-white text-decoration-none d-block">Accueil</a>
    </button>
</form>
