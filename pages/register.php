<?php

include_once './config/db.php';
include_once './pages/logger.php';
session_start();

$maxpwd = 45;
$specialpwd = '/[!@#$%&*?A-Z\d]+/';
$regexemail = '/^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/';
$hasError = false;

if (isset($_POST) && !empty($_POST)) {
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $pwd = htmlspecialchars(trim($_POST["pwd"]));
    $repwd = htmlspecialchars(trim($_POST["repwd"]));

    $sql = "SELECT id FROM user WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $userExists = $stmt->fetch();

    if ($username === '') {
        echo('user empty');
        $hasError = true;
    }
    if ($userExists) {
        echo('user empty');
        $hasError = true;
    }
    if ($pwd === '') {
        echo('user empty');
        $hasError = true;
    }
    if (strlen($pwd) > $maxpwd || !preg_match($specialpwd, $pwd)) {
        echo('user empty');
        $hasError = true;
    }
    if ($repwd === '' || $pwd !== $repwd) {
        echo('user empty');
        $hasError = true;
    }
    if ($email === '') {
        echo('user empty');
        $hasError = true;
    }
    if (!preg_match($regexemail, $email)) {
        echo('user empty');
        $hasError = true;
    }
    if ($hasError) {
        header("Location: index.php?page=register");
    } else {

        $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `user`(`username`, `email`, `password`) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $pwd]);
        $id = $pdo->lastInsertId();
        logAction("create", $username, $id);
        echo '<div class="sub-success"> Your account has been created! We are redirecting you :)) </div>';
        echo "<script>setTimeout(() => {
    window.location.href = 'index.php';}, 100000); </script>";
    }
    exit;

}
?>

<link href="./assets/css/register-login.css" rel="stylesheet">
<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <h1 class="text-center mb-4">Inscription</h1>
            <form action="#" method="post" class="needs-validation" novalidate>

                <div class="mb-3">
                    <label for="username" class="form-label visually-hidden">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" class="form-control form-control-lg" placeholder="Nom d'utilisateur" required>
                </div>

                <div class="mb-3">
                    <label for="pwd" class="form-label visually-hidden">Mot de passe</label>
                    <input id="pwd" type="password" name="pwd" class="form-control form-control-lg" placeholder="Mot de passe" required>
                    <ul class="text-muted small mt-2 list-unstyled" id="pwd-condition">
                        <li><small>45 caractères maximum</small></li>
                        <li><small>Doit inclure un caractère spécial</small></li>
                        <li><small>Doit contenir au moins une majuscule</small></li>
                        <li><small>Doit contenir un chiffre</small></li>
                    </ul>
                </div>

                <div class="mb-3">
                    <label for="repwd" class="form-label visually-hidden">Confirmation mot de passe</label>
                    <input id="repwd" type="password" name="repwd" class="form-control form-control-lg" placeholder="Confirmer le mot de passe" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label visually-hidden">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email" required>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" style="background:#613F75; border-radius:8px;">S'inscrire</button>
            </form>

            <button class="btn btn-secondary btn-lg w-100" type="button">
                <a href="?page=dashboard" class="text-white text-decoration-none d-block">Accueil</a>
            </button>
        </div>
    </div>
</section>

