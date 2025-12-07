<?php
require_once 'config/release.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    header('Location: index.php?page=login');
    exit();
}

$user_id = $_SESSION['user']['id'];
$message = "";

// Condition de demande de la page au serveur pour activer Profil Actif/Inactif
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_toggle_visibility'])) {
    try {
        // Prepare pour éviter injection de WHERE
        $stmt = $pdo->prepare("UPDATE user SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$user_id]);
        $message = "<div class='alert alert-success alert-dismissible fade show'>Votre statut de visibilité a été mis à jour.</div>";

        $stmtRefresh = $pdo->prepare("SELECT is_active FROM user WHERE id = ?");
        $stmtRefresh->execute([$user_id]);
        // Retour d'une seule information
        $refreshState = $stmtRefresh->fetchColumn();
        // Mise à jour du user
        $_SESSION['user']['is_active'] = $refreshState;

    } catch (Exception $e) {
        $message = "<div class='alert alert-danger'>Erreur : Impossible de changer le statut.</div>";
    }
}

$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<div class="container py-5">

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">Bonjour, <?= htmlspecialchars($user['firstname']) ?> ! 👋</h1>
            <p class="text-muted">Gérez votre carrière et vos messages depuis cet espace.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if ($user['is_active']): ?>
                <span class="badge bg-success px-3 py-2 rounded-pill"> Visible par les recruteurs</span>
            <?php else: ?>
                <span class="badge bg-secondary px-3 py-2 rounded-pill"> Profil Masqué</span>
            <?php endif; ?>
        </div>
    </div>

    <?= $message ?>

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4 text-center p-3">
                <div class="card-body">
                    <?php
                    $photo = !empty($user['picture']) ? $user['picture'] : 'https://via.placeholder.com/150';
                    ?>
                    <img src="<?= htmlspecialchars($photo) ?>"
                         class="rounded-circle mb-3 border border-4 border-white shadow-sm"
                         style="width: 120px; height: 120px; object-fit: cover; margin-top: -50px; background: #fff;">

                    <h4 class="card-title fw-bold"><?= htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']) ?></h4>
                    <p class="text-primary mb-1"><?= !empty($user['job_title']) ? htmlspecialchars($user['job_title']) : 'Poste non défini' ?></p>
                    <p class="text-muted small">@<?= htmlspecialchars($user['username']) ?></p>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="index.php?page=profile-guest&id=<?= $user['id'] ?>" class="btn btn-outline-primary">
                            Voir mon profil public
                        </a>
                        <a href="index.php?page=resume&id=<?= $user['id'] ?>" class="btn btn-dark">
                            Voir mon CV (PDF)
                        </a>
                        <a href="index.php?page=profile-edit" class="btn btn-secondary mt-2">
                            Modifier mes infos
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">État du profil</h6>
                    <form method="POST">
                        <input type="hidden" name="action_toggle_visibility" value="1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="visibilitySwitch"
                                   onchange="this.form.submit()" <?= $user['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="visibilitySwitch">
                                <?= $user['is_active'] ? 'Actif (Visible)' : 'Inactif (Caché)' ?>
                            </label>
                        </div>
                        <p class="small text-muted mt-2">
                            En désactivant cette option, vous n'apparaîtrez plus dans les résultats de recherche des
                            recruteurs.
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Messagerie Directe <span class="badge bg-danger rounded-pill ms-2"
                                                                      style="font-size: 0.7rem;">Bientôt</span></h5>
                    <button class="btn btn-sm btn-light rounded-circle"></button>
                </div>

                <div class="card-body p-0 d-flex flex-column" style="height: 500px;">

                    <div class="flex-grow-1 p-4" style="overflow-y: auto; background-color: #f8f9fa;">

                        <div class="d-flex mb-4">
                            <img src="https://via.placeholder.com/40" class="rounded-circle me-3" width="40"
                                 height="40">
                            <div>
                                <div class="bg-white p-3 rounded-3 shadow-sm border">
                                    <p class="mb-1 fw-bold small text-dark">Recruteur TechCorp</p>
                                    <p class="mb-0 text-muted">Bonjour <?= htmlspecialchars($user['firstname']) ?>,
                                        votre profil nous intéresse beaucoup pour un poste de développeur Fullstack.
                                        Êtes-vous disponible pour un échange ?</p>
                                </div>
                                <small class="text-muted ms-1" style="font-size: 0.75rem;">10:30</small>
                            </div>
                        </div>

                        <div class="d-flex mb-4 justify-content-end">
                            <div class="text-end">
                                <div class="p-3 rounded-3 shadow-sm text-white" style="background-color: #613F75;">
                                    <p class="mb-0">Bonjour ! Merci pour votre intérêt. Je suis disponible cet
                                        après-midi ou demain matin.</p>
                                </div>
                                <small class="text-muted me-1" style="font-size: 0.75rem;">10:45 </small>
                            </div>
                        </div>


                        <div class="d-flex align-items-center text-muted small ms-5 ps-2">
                            <div class="spinner-grow spinner-grow-sm me-1" role="status"
                                 style="width: 0.5rem; height: 0.5rem;"></div>
                            <div class="spinner-grow spinner-grow-sm me-1" role="status"
                                 style="width: 0.5rem; height: 0.5rem; animation-delay: 0.2s"></div>
                            <div class="spinner-grow spinner-grow-sm" role="status"
                                 style="width: 0.5rem; height: 0.5rem; animation-delay: 0.4s"></div>
                            <span class="ms-2 fst-italic">Recruteur TechCorp écrit...</span>
                        </div>

                    </div>

                    <div class="p-3 border-top bg-white">
                        <div class="input-group">
                            <button class="btn btn-light border" type="button"></button>
                            <input type="text" class="form-control border-start-0 border-end-0"
                                   placeholder="Écrivez votre message..." aria-label="Message">
                            <button class="btn btn-light border-start-0 border" type="button"></button>
                            <button class="btn text-white fw-bold px-4" type="button"
                                    style="background-color: #613F75;">

                            </button>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted" style="font-size: 0.7rem;"> Vos messages sont chiffrés de bout en
                                bout (Simulation)</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>