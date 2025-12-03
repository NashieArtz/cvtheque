<?php

include_once './config/db.php';
include_once './pages/logger.php';

if (isset($_POST) && !empty($_POST)) {
  $username = htmlspecialchars(trim($_POST["username"]));
  $email = htmlspecialchars(trim($_POST["email"]));
  $pwd = htmlspecialchars(trim($_POST["pwd"]));
  $repwd = htmlspecialchars(trim($_POST["repwd"]));

    $sql = "SELECT id FROM user WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $userExists = $stmt->fetch();

  $pwd = password_hash($pwd, PASSWORD_DEFAULT);
  $sql = "INSERT INTO `user`(`username`, `email`, `pwd`) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$username, $email, $pwd]);
  $id = $pdo->lastInsertId();
  logAction("create", $username, $id);
  echo '<div class="sub-success"> Your account has been created! We are redirecting you :)) </div>';
  echo "<script>setTimeout(() => {
      window.location.href = 'index.php';
    }, 2000); </script>";
}
?>

<section class="container my-5 register-page">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <h1 class="text-center mb-4">Inscription</h1>
            <form action="#" method="post" class="needs-validation">

                <div class="mb-3">
                    <label for="username" class="form-label visually-hidden">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username-register" class="form-control register-input"
                           placeholder="Nom d'utilisateur" required>
                    <p id="username-register-p" class="text-danger">

                    </p>
                    <p id="username-taken-p" class="text-danger">

                    </p>

                </div>

                <div class="mb-3">
                    <label for="pwd" class="form-label visually-hidden">Mot de passe</label>
                    <input id="pwd-register" type="password" name="pwd" class="form-control register-input"
                           placeholder="Mot de passe" required>
                    <ul class="text-muted small mt-2" id="pwd-condition">
                        <li id="pwd-45" class="text-danger">45 caractères maximum</li>
                        <li id="pwd-specialchar" class="text-danger">Doit inclure un caractère spécial</li>
                        <li id="pwd-maj" class="text-danger">Doit contenir au moins une majuscule</li>
                        <li id="pwd-number" class="text-danger">Doit contenir un chiffre</li>
                    </ul>
                    <p id="pwd-register-p">

                    </p>
                </div>

                <div class="mb-3">
                    <label for="repwd" class="form-label visually-hidden">Confirmation mot de passe</label>
                    <input id="repwd-register" type="password" name="repwd" class="form-control register-input"
                           placeholder="Confirmer le mot de passe" required>
                    <p id="repwd-register-p">

                    </p>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label visually-hidden">Email</label>
                    <input type="email" name="email" id="email-register" class="form-control" placeholder="Email" required>
                    <p id="email-register-p">

                    </p>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                        style="background:#613F75; border-radius:8px;">S'inscrire
                </button>
            </form>

            <button class="btn btn-secondary btn-lg w-100" type="button">
                <a href="?page=dashboard" class="text-white text-decoration-none d-block">Accueil</a>
            </button>
        </div>
    </div>
</section>

