<?php
include_once './config/db.php';


if (isset($_POST) && !empty($_POST)) {
    $username = htmlspecialchars(trim($_POST['username']));
    $pwd = htmlspecialchars(trim($_POST['password']));
    $error = [];

    $sql = "SELECT id, username, email, pwd, role_id FROM `user` WHERE `username` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    try {
        if ($user && password_verify($pwd, $user['pwd'])) {
            $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
            ];
            $_SESSION['welcome_message'] = "Bienvenue " . htmlspecialchars($user['username']) . "!";

            header('Location: index.php?page=profile-user&id=' . $user['id']);
            exit;
        } elseif ($user && $pwd === $user['pwd']) {
            $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
            ];
            $_SESSION['welcome_message'] = "Bienvenue " . htmlspecialchars($user['username']) . "!";
            header('Location: index.php?page=profile&id=' . $user['id']);
            exit;

        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Une erreur de base de données est survenue.";
    }
}

?>


<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <h1 class="text-center mb-4">Connexion</h1>
            <?php
            if (isset($error) && $error) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
            }
            ?>
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