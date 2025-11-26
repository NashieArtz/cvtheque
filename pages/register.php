<?php

$maxpwd = 45;
$specialpwd = '/[!@#$%&*?A-Z\d]+/';
$regexemail = '/^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/';
$hasError = false;

if (isset($_POST) && !empty($_POST)) {
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $pwd = htmlspecialchars(trim($_POST["pwd"]));
    $repwd = htmlspecialchars(trim($_POST["repwd"]));
    $role = htmlspecialchars(trim($_POST["role"]));

    $sql = "SELECT id FROM user WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $userExists = $stmt->fetch();

    if ($username === '') {
        $_SESSION['flash_message'] = "Nom d'utilisateur absent !";
        $_SESSION['flash_type'] = "error";
        $hasError = true;
    }
    if ($userExists) {
        $_SESSION['flash_message'] = "Cet utilisateur existe déjà !";
        $_SESSION['flash_type'] = "error";
        $hasError = true;
    }
    if ($pwd === '') {
        $_SESSION['flash_message'] = "Mot de passe absent !";
        $_SESSION['flash_type'] = "error";
        $hasError = true;
    }
    if (strlen($pwd) > $maxpwd || !preg_match($specialpwd, $pwd)) {
        $_SESSION['flash_message'] = "Le mot de passe ne de répond pas aux exigences !";
        $_SESSION['flash_type'] = "error";
        $hasError = true;
    }
    if ($repwd === '' || $pwd !== $repwd) {
        $_SESSION['flash_message'] = "Les mots de passe ne sont pas identiques !";
        $_SESSION['flash_type'] = "error";
        $hasError = true;
    }
  if ($email === '') {
    $_SESSION['flash_message'] = "Adresse e-mail absente !";
    $_SESSION['flash_type'] = "error";
    $hasError = true;
  }
    if (!preg_match($regexemail, $email)) {
        $_SESSION['flash_message'] = "Merci d'utiliser une adresse e-mail valide.";
        $_SESSION['flash_type'] = "error";
        $hasError = true;
    }
    if ($role === '') {
    $_SESSION['flash_message'] = "Merci d'indiquer votre type de profil.";
    $_SESSION['flash_type'] = "error";
    $hasError = true;
  }
    if ($hasError){
        header("Location: index.php?page=register");
    } else {

        $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `user`(`username`, `email`, `password`,) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $pwd]);
        $id = $pdo->lastInsertId();
        logAction("create", $username, $id);
        echo '<div class="sub-success"> Votre compte a été créé avec succès ! </div>';
        echo "<script>setTimeout(() => {
            window.location.href = 'index.php';
          }, 100000); </script>";
    }
    exit;?>
<form method="post">

  <label for="firstname">Prénom :
    <input type="text" name="firstname">
  </label>
  <label for="lastname">Nom :
    <input type="text" name="lastname">
  </label>
  <label for="username">Nom d'utilisateur :
    <input type="text" name="username">
  </label>
  <label for="pwd">Mot de passe :
    <input type="password" name="pwd">
  </label>
  <label for="repwd">Confirmez votre mot de passe :
    <input type="password" name="repwd">
  </label>
  <label for="email">Adresse e-mail :
    <input type="email" name="email">
  </label>
  <label for="role">Vous êtes :
    <select name="role">
      <option value="student">Étudiant</option>
      <option value="employer">Recruteur</option>
    </select>
  </label>
  <button type="submit">Inscription</button>

</form>
<?php

include_once "../includes/footer.php";
?>
