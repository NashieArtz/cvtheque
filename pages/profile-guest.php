<?php
include 'config/release.php';

if (isset($_SESSION['welcome_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['welcome_message'] . '</div>';
    unset($_SESSION['welcome_message']);
}

// Sécurité de intval(), GET la 1ère valeur de l'URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT u.*, a.city, a.area_code, a.street_name, c.name as country_name 
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id 
            LEFT JOIN country c ON u.country_id = c.id 
            WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$r) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Utilisateur introuvable.</div></div>";
    exit();
}

$stmtExp = $pdo->prepare("SELECT * FROM experience WHERE user_id = ? ORDER BY date_start DESC");
$stmtExp->execute([$user_id]);
$experiences = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

$stmtSkills = $pdo->prepare("SELECT s.* FROM skills s 
                             JOIN user_has_skills uhs ON s.id = uhs.skills_id 
                             WHERE uhs.user_id = ?");
$stmtSkills->execute([$user_id]);
$skills = $stmtSkills->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body p-4 p-md-5" style="background-color: #f0f0f0;">

                    <div class="row align-items-center">
                        <div class="col-12 col-md-3 text-center mb-4 mb-md-0">
                            <?php
                            $photoUrl = !empty($r['picture']) ? htmlspecialchars($r['picture']) : "https://via.placeholder.com/150";
                            ?>
                            <img src="<?= $photoUrl ?>" alt="Avatar"
                                 class="img-fluid rounded-circle shadow-lg"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        </div>

                        <div class="col-12 col-md-6 text-center text-md-start">
                            <h1 class="display-5 fw-bold mb-0">
                                <?= htmlspecialchars($r['firstname']) ?> <?= htmlspecialchars($r['lastname']) ?>
                            </h1>
                            <p class="lead" style="color: #613F75;">
                                <?= !empty($r['job_title']) ? htmlspecialchars($r['job_title']) : 'Étudiant / Postulant' ?>
                            </p>
                            <p class="text-muted mb-0">@<?= htmlspecialchars($r['username']) ?></p>
                        </div>

                        <div class="col-12 col-md-3 text-center text-md-end">
                            <?php
                            if (isset($_SESSION['user']) && $_SESSION['user']['role_id'] == 3) {
                                echo '<a href="index.php?page=profile-edit&id=' . $r['id'] . '" class="btn btn-warning"><i class="fas fa-edit"></i> Éditer (Admin)</a>';
                            } elseif (isset($_SESSION['user']) && $_SESSION['user']['id'] == $r['id']) {
                                echo '<a href="index.php?page=profile-edit" class="btn btn-secondary"><i class="fas fa-pen"></i> Modifier mon profil</a>';
                            }
                            ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold">Détails Personnels & Localisation</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h5 class="fw-bold">Coordonnées</h5>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-envelope me-2"></i> <?= htmlspecialchars($r['email']) ?>
                                    </p>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-phone me-2"></i> <?= !empty($r['phone']) ? htmlspecialchars($r['phone']) : 'Non renseigné' ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="fw-bold">Localisation</h5>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-globe-europe me-2"></i> Pays : <?= !empty($r['country_name']) ? htmlspecialchars($r['country_name']) : 'Non renseigné' ?>
                                    </p>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-city me-2"></i> Ville : <?= !empty($r['city']) ? htmlspecialchars($r['city']) : 'Non renseigné' ?>
                                    </p>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-map-pin me-2"></i> CP : <?= !empty($r['area_code']) ? htmlspecialchars($r['area_code']) : '' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold">Compétences</div>
                        <div class="card-body">
                            <?php if (empty($skills)): ?>
                                <p class="text-muted fst-italic">Aucune compétence listée.</p>
                            <?php else: ?>
                                <ul class="list-inline mb-0">
                                    <?php foreach ($skills as $skill): ?>
                                        <?php if (!empty($skill['hard_skills'])): ?>
                                            <li class="list-inline-item mb-2">
                                                <span class="badge bg-primary fs-6"><?= htmlspecialchars($skill['hard_skills']) ?></span>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($skill['soft_skills'])): ?>
                                            <li class="list-inline-item mb-2">
                                                <span class="badge bg-secondary fs-6"><?= htmlspecialchars($skill['soft_skills']) ?></span>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($skill['hobbies'])): ?>
                                            <li class="list-inline-item mb-2">
                                                <span class="badge bg-secondary fs-6"><?= htmlspecialchars($skill['hobbies']) ?></span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold">Expériences Professionnelles</div>
                        <div class="card-body">
                            <?php if (empty($experiences)): ?>
                                <p class="text-muted fst-italic">Aucune expérience renseignée.</p>
                            <?php else: ?>
                                <dl class="row mb-0">
                                    <?php foreach ($experiences as $exp): ?>
                                        <dt class="col-sm-4 text-dark fw-normal">
                                            <?= htmlspecialchars($exp['jobtitle']) ?>
                                            <?php if(!empty($exp['employer'])): ?>
                                                <span class="text-primary">(<?= htmlspecialchars($exp['employer']) ?>)</span>
                                            <?php endif; ?>
                                        </dt>
                                        <dd class="col-sm-8 text-muted">
                                            <?= htmlspecialchars($exp['date_start']) ?>
                                            <?= !empty($exp['date_end']) ? ' à ' . htmlspecialchars($exp['date_end']) : ' - Aujourd\'hui' ?>
                                        </dd>

                                        <?php if (!empty($exp['description'])): ?>
                                            <dt class="col-sm-4"></dt>
                                            <dd class="col-sm-8 small fst-italic mb-3">
                                                <?= nl2br(htmlspecialchars($exp['description'])) ?>
                                            </dd>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dl>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold">Informations Complémentaires</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" disabled <?= (!empty($r['driver_licence']) && $r['driver_licence'] == 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label text-muted">Permis de Conduire</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" disabled <?= ($r['is_active'] == 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label text-muted">Profil Actif (Visible)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>