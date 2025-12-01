<?php
if (isset($_POST) && !empty($_POST)) {
    $username = htmlspecialchars(trim($_POST['username']));
    $pwd = htmlspecialchars(trim($_POST['password']));

    $sql = "SELECT * FROM `user` WHERE `username` LIKE '$username'";
    $stmt = $pdo->query($sql)->fetch();
    if (password_verify($pwd, $stmt['pwd'])) {
        $_SESSION['user'] = [
                'id' => $stmt['id'],
                'username' => $stmt['username'],
                'email' => $stmt['email'],
                'role_id' => $stmt['role_id']
        ];
        header('Location: index.php?page=profile&id=' . $stmt['id']);
    } else if ($pwd === $stmt['pwd']) {
        $_SESSION['user'] = [
                'id' => $stmt['id'],
                'username' => $stmt['username'],
                'email' => $stmt['email'],
                'role_id' => $stmt['role_id']
        ];
        header('Location: index.php?page=profile&id=' . $stmt['id']);
    }
}

?>


<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <h1 class="text-center mb-4">Connexion</h1>
            <form action="#" method="post" class="needs-validation">
                <label for="username" class="form-label visually-hidden">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" placeholder="Nom d'utilisateur" required>
                <label for="password" class="form-label visually-hidden">Mots de passe</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                        style="background:#613F75; border-radius:8px;">Connexion
                </button>
            </form>
            <button class="btn btn-secondary btn-lg w-100" type="button">
                <a href="?page=dashboard" class="text-white text-decoration-none d-block">Accueil</a>
            </button>
        </div>
    </div>
</section>