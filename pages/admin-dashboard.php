<?php
include './config/roles/admin.php';
include_once './config/search-logic.php';

$message = "";

// Sécurité de intval(), GET la 1ère valeur de l'URL
// REQUEST_METHOD: Check si l'user a envoyé une requête, avec données ou non
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id']);

    if (isset($_POST['action_delete'])) {
        try {
            // Soit toutes passent, soit aucune
            // Suppression des données liées, puis le user
            $pdo->beginTransaction();
            $pdo->prepare("DELETE FROM user_has_skills WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM user_has_education WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM experience WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM address WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM user WHERE id = ?")->execute([$userId]);
            // Validation des suppressions
            $pdo->commit();
            $message = "<div class='alert alert-success'>Utilisateur supprimé.</div>";
        } catch (Exception $e) {
            // Annuler en cas d'erreur
            $pdo->rollBack();
            $message = "<div class='alert alert-danger'>Erreur: " . $e->getMessage() . "</div>";
        }
    } elseif (isset($_POST['action_toggle'])) {
        try {
            $stmt = $pdo->prepare("UPDATE user SET is_active = NOT is_active WHERE id = ?");
            $stmt->execute([$userId]);
            $message = "<div class='alert alert-info'>Statut mis à jour.</div>";
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Erreur mise à jour.</div>";
        }
    }
}

// GET les filtres en paramètres
$filters = [
        'search' => $_GET['search'] ?? '',
        'filter_status' => $_GET['filter_status'] ?? 'all',
        'filter_city' => $_GET['filter_city'] ?? '',
        'filter_skill' => $_GET['filter_skill'] ?? '',
        'filter_license' => $_GET['filter_license'] ?? 'all'
];

$search = $filters['search'];
$filterStatus = $filters['filter_status'];
$filterCity = $filters['filter_city'];
$filterSkill = $filters['filter_skill'];
$filterLicense = $filters['filter_license'];


$users = searchProfiles($pdo, $filters, true);

// Check si un filtre est utilisé
$isFilterActive = !empty($search) || !empty($filterCity) || !empty($filterSkill) || $filterLicense !== 'all' || $filterStatus !== 'all';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Administration des Profils</h3>
        <span class="badge bg-primary fs-6 rounded-pill px-3"><?= count($users) ?> profils</span>
    </div>

    <?= $message ?>

    <div class="row justify-content-center mb-5">
        <div class="col-md-12">

            <form action="index.php" method="GET" class="search-bar-container">
                <input type="hidden" name="page" value="admin-dashboard">

                <div class="d-flex flex-wrap gap-3 align-items-center">

                    <button class="btn btn-purple px-4 py-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filtersCollapse">
                         Filter
                    </button>

                    <div class="flex-grow-1 position-relative">
                        <input type="text" name="search" class="form-control input-search-custom"
                               placeholder="Rechercher par mot-clé (Nom, Email, Ville...)"
                               value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="search-btn">
                             Rechercher
                        </button>
                    </div>
                </div>

                <div class="collapse mt-3 <?= ($isFilterActive) ? 'show' : '' ?>" id="filtersCollapse">
                    <div class="row g-3 pt-2">
                        <div class="col-md-3">
                            <input type="text" name="filter_city" class="form-control" placeholder="Ville"
                                   value="<?= htmlspecialchars($filterCity) ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="filter_skill" class="form-control" placeholder="Compétence"
                                   value="<?= htmlspecialchars($filterSkill) ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="filter_license" class="form-select">
                                <option value="all">Permis : Tous</option>
                                <option value="yes" <?= $filterLicense === 'yes' ? 'selected' : '' ?>>Requis</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="filter_status"
                                    class="form-select <?= $filterStatus !== 'all' ? 'border-primary' : '' ?>">
                                <option value="all">Statut : Tous</option>
                                <option value="active" <?= $filterStatus === 'active' ? 'selected' : '' ?>>Actifs
                                </option>
                                <option value="inactive" <?= $filterStatus === 'inactive' ? 'selected' : '' ?>>
                                    Inactifs
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100">Appliquer</button>
                        </div>
                    </div>

                    <?php if ($isFilterActive): ?>
                        <div class="mt-2 text-end">
                            <a href="index.php?page=admin-dashboard" class="text-decoration-none text-muted small">
                                 Effacer les filtres
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($users as $user): ?>
            <?php
        // Condition faire apparaitre l'user
            $isActive = isset($user['is_active']) ? $user['is_active'] : 1;
            ?>
            <div class="col-md-6">
                <div class="card-profile-horizontal position-relative <?= !$isActive ? 'border-danger' : '' ?>">

                    <?php if (!$isActive): ?>
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-danger">INACTIF</span>
                        </div>
                    <?php endif; ?>

                    <div class="profile-header">
                        <div class="profile-img-container">
                            <?php
                            $imgSrc = !empty($user['picture']) ? htmlspecialchars($user['picture']) : 'https://via.placeholder.com/150?text=IMG';
                            ?>
                            <img src="<?= $imgSrc ?>" alt="Avatar" class="profile-img-left"
                                 style="<?= !$isActive ? 'filter: grayscale(100%); opacity: 0.7;' : '' ?>">
                        </div>

                        <div class="profile-info flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5><?= htmlspecialchars($user['lastname']) ?> <?= htmlspecialchars($user['firstname']) ?></h5>

                                    <span class="badge bg-info text-dark mb-2" style="font-size: 0.75rem;">
                                        <?= htmlspecialchars($user['role_name'] ?? 'Utilisateur') ?>
                                    </span>
                                </div>
                                <span class="badge bg-secondary">#<?= $user['id'] ?></span>
                            </div>

                            <div class="profile-job">
                                <?= !empty($user['job_title']) ? htmlspecialchars($user['job_title']) : 'Aucun poste défini' ?>
                            </div>
                            <div class="profile-location">
                                 <?= !empty($user['city']) ? htmlspecialchars($user['city']) : 'Ville non renseignée' ?>
                            </div>
                        </div>
                    </div>

                    <div class="skills-section">
                        <div class="skills-title">Compétences</div>
                        <ul class="skills-list">
                            <?php
                            if (!empty($user['skills_list'])) {
                                // explode(): Faire exploser chaine de txt en tableau avec séparateur
                                $skills = explode(',', $user['skills_list']);
                                $skillsToShow = array_slice($skills, 0, 3);
                                foreach ($skillsToShow as $skillName) {
                                    echo "<li>" . htmlspecialchars(trim($skillName)) . "</li>";
                                }
                                if (count($skills) > 3) {
                                    echo "<li style='list-style: none; color: #888; font-size:0.8rem;'>+ " . (count($skills) - 3) . " autres</li>";
                                }
                            } else {
                                echo "<li style='list-style: none; font-style: italic; color: #999;'>Aucune compétence</li>";
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="card-footer-custom mt-auto pt-3 border-top">
                        <form method="POST" class="d-flex w-100 gap-2 align-items-center">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                            <a href="index.php?page=profile-guest&id=<?= $user['id'] ?>"
                               class="btn btn-outline-primary" title="Voir le profil public">
                                Voir
                            </a>

                            <button type="submit" name="action_toggle"
                                    class="btn btn-sm w-50 <?= $isActive ? 'btn-outline-warning' : 'btn-outline-success' ?> flex-grow-1"
                                    title="<?= $isActive ? 'Désactiver ce compte' : 'Activer ce compte' ?>">
                                <i class="bi <?= $isActive ? 'bi-pause-circle' : 'bi-play-circle' ?>"></i>
                                <?= $isActive ? 'Désactiver' : 'Activer' ?>
                            </button>

                            <button type="submit" name="action_delete" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur et toutes ses données ?');"
                                    title="Supprimer définitivement">
                                Supprimer
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($users)): ?>
            <div class="col-12 text-center py-5">
                <h3 class="text-muted">Aucun profil trouvé.</h3>
                <a href="index.php?page=admin-dashboard" class="btn btn-outline-secondary">Réinitialiser</a>
            </div>
        <?php endif; ?>
    </div>
</div>