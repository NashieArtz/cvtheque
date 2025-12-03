<?php
require_once 'config/release.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    header('Location: index.php?page=login');
    exit();
}

$user_id = $_SESSION['user']['id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_toggle_visibility'])) {
    try {
        $stmt = $pdo->prepare("UPDATE user SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$user_id]);
        $message = "<div class='alert alert-success alert-dismissible fade show'>Votre statut de visibilité a été mis à jour.</div>";

        $stmtRefresh = $pdo->prepare("SELECT is_active FROM user WHERE id = ?");
        $stmtRefresh->execute([$user_id]);
        $refreshState = $stmtRefresh->fetchColumn();
        $_SESSION['user']['is_active'] = $refreshState;

    } catch (Exception $e) {
        $message = "<div class='alert alert-danger'>Erreur : Impossible de changer le statut.</div>";
    }
}

$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nbExp = $pdo->prepare("SELECT COUNT(*) FROM experience WHERE user_id = ?");
$nbExp->execute([$user_id]);
$countExp = $nbExp->fetchColumn();

$nbEdu = $pdo->prepare("SELECT COUNT(*) FROM user_has_education WHERE user_id = ?");
$nbEdu->execute([$user_id]);
$countEdu = $nbEdu->fetchColumn();

$nbSkills = $pdo->prepare("SELECT COUNT(*) FROM user_has_skills WHERE user_id = ?");
$nbSkills->execute([$user_id]);
$countSkills = $nbSkills->fetchColumn();

$progress = 0;
if (!empty($user['picture'])) $progress += 10;
if (!empty($user['job_title'])) $progress += 10;
if (!empty($user['phone'])) $progress += 10;
if (!empty($user['email'])) $progress += 10;
if ($countExp > 0) $progress += 20;
if ($countExp > 1) $progress += 10;
if ($countEdu > 0) $progress += 20;
if ($countSkills > 0) $progress += 10;

$progressColor = 'bg-danger';
if($progress > 50) $progressColor = 'bg-warning';
if($progress > 80) $progressColor = 'bg-success';

?>

<div class="container py-5">

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">Bonjour, <?= htmlspecialchars($user['firstname']) ?> ! 👋</h1>
            <p class="text-muted">Gérez votre carrière et votre visibilité depuis cet espace.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if($user['is_active']): ?>
                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-eye"></i> Visible par les recruteurs</span>
            <?php else: ?>
                <span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="bi bi-eye-slash"></i> Profil Masqué</span>
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
                    <img src="<?= htmlspecialchars($photo) ?>" class="rounded-circle mb-3 border border-4 border-white shadow-sm"
                         style="width: 120px; height: 120px; object-fit: cover; margin-top: -50px; background: #fff;">

                    <h4 class="card-title fw-bold"><?= htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']) ?></h4>
                    <p class="text-primary mb-1"><?= !empty($user['job_title']) ? htmlspecialchars($user['job_title']) : 'Poste non défini' ?></p>
                    <p class="text-muted small">@<?= htmlspecialchars($user['username']) ?></p>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="index.php?page=profile-guest&id=<?= $user['id'] ?>" class="btn btn-outline-primary">
                            <i class="bi bi-person-badge"></i> Voir mon profil public
                        </a>
                        <a href="index.php?page=resume&id=<?= $user['id'] ?>" class="btn btn-dark">
                            <i class="bi bi-file-earmark-pdf"></i> Voir mon CV (PDF)
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
                            <input class="form-check-input" type="checkbox" id="visibilitySwitch" onchange="this.form.submit()" <?= $user['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="visibilitySwitch">
                                <?= $user['is_active'] ? 'Actif (Visible)' : 'Inactif (Caché)' ?>
                            </label>
                        </div>
                        <p class="small text-muted mt-2">
                            En désactivant cette option, vous n'apparaîtrez plus dans les résultats de recherche des recruteurs.
                        </p>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title fw-bold mb-0">Complétion du profil</h5>
                        <span class="badge <?= $progressColor ?>"><?= $progress ?>%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar <?= $progressColor ?>" role="progressbar" style="width: <?= $progress ?>%"></div>
                    </div>

                    <?php if($progress < 100): ?>
                        <div class="alert alert-light mt-3 mb-0 border">
                            <strong><i class="bi bi-lightbulb"></i> Conseil :</strong>
                            <?php if(empty($user['picture'])): ?> Ajoutez une photo de profil. <?php endif; ?>
                            <?php if($countExp == 0): ?> Ajoutez vos expériences professionnelles. <?php endif; ?>
                            <?php if($countSkills == 0): ?> Ajoutez des compétences. <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-success mt-2 small"><i class="bi bi-check-circle-fill"></i> Votre profil est optimisé au maximum !</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 bg-light">
                        <div class="card-body text-center">
                            <h2 class="display-4 fw-bold text-primary"><?= $countExp ?></h2>
                            <p class="text-muted mb-0">Expériences</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 bg-light">
                        <div class="card-body text-center">
                            <h2 class="display-4 fw-bold text-info"><?= $countEdu ?></h2>
                            <p class="text-muted mb-0">Formations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 bg-light">
                        <div class="card-body text-center">
                            <h2 class="display-4 fw-bold text-warning"><?= $countSkills ?></h2>
                            <p class="text-muted mb-0">Compétences</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-primary shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-primary">Mettre à jour mes informations</h5>
                        <p class="mb-0 text-muted small">Ajoutez une nouvelle expérience, changez votre photo ou mettez à jour vos coordonnées.</p>
                    </div>
                    <a href="index.php?page=profile-edit" class="btn btn-primary px-4">
                        <i class="bi bi-pencil-square"></i> Modifier tout
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>