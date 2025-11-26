<?php
if (isset($_POST) && !empty($_POST)) {
  $username = htmlspecialchars(trim($_POST['username']));
  $pwd = htmlspecialchars(trim($_POST['pwd']));

  $sql="SELECT * FROM `user` WHERE `username` LIKE '$username'";
  $stmt= $pdo->query($sql)->fetch();
  if (password_verify($pwd, stmt['password'])){
    session start();
  }
};

?>
<link href="./assets/css/register-login.css" rel="stylesheet">
<form method="post">
  <label for="username">Nom d'utilisateur</label>
  <input type="text" name="username" id="username">
  <label for="password">Mots de passe</label>
  <input type="password" name="password" id="password">
  <input type="submit" name="button" id="button">
</form>
