<?php
include './config/roles/admin.php';


$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id']);

    if (isset($_POST['action_delete'])) {
        try {
            $pdo->beginTransaction();
            $pdo->prepare("DELETE FROM user_has_skills WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM user_has_education WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM experience WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM address WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM user WHERE id = ?")->execute([$userId]);
            $pdo->commit();
            $message = "<div class='alert alert-success'>Utilisateur supprimé.</div>";
        } catch (Exception $e) {
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

$search = $_GET['search'] ?? '';
$filterStatus = $_GET['filter_status'] ?? 'all';

$sql = "SELECT u.*, r.name as role_name 
        FROM user u 
        LEFT JOIN role r ON u.role_id = r.id 
        WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (u.firstname LIKE :search OR u.lastname LIKE :search OR u.job_title LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($filterStatus === 'active') {
    $sql .= " AND u.is_active = 1";
} elseif ($filterStatus === 'inactive') {
    $sql .= " AND (u.is_active = 0 OR u.is_active IS NULL)";
}

$sql .= " ORDER BY u.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Administration des Profils</h1>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-10">
            <div class="d-flex gap-3 align-items-center">

                <div class="flex-grow-1">
                    <form action="index.php" method="GET" class="card card-body shadow-sm border-0 p-0">
                        <input type="hidden" name="page" value="profiles-list">
                        <input type="hidden" name="filter_status" value="<?= htmlspecialchars($filterStatus) ?>">

                        <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                   placeholder="Rechercher un développeur, une ville, un nom..."
                                   value="<?= htmlspecialchars($search) ?>">
                            <button class="btn btn-primary px-4" type="submit">Rechercher</button>
                        </div>
                    </form>
                </div>

                <div class="dropdown">
                    <?php
                    $buttonText = 'Statut: Tous';
                    $buttonClass = 'btn-secondary';
                    if ($filterStatus === 'active') {
                        $buttonText = 'Statut: Actifs';
                        $buttonClass = 'btn-success';
                    } elseif ($filterStatus === 'inactive') {
                        $buttonText = 'Statut: Inactifs';
                        $buttonClass = 'btn-danger';
                    }

                    $currentPage = $_GET['page'] ?? 'profiles-list';
                    ?>
                    <button class="btn <?= $buttonClass ?> dropdown-toggle shadow-sm" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $buttonText ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">

                        <li><a class="dropdown-item <?= $filterStatus === 'all' ? 'active' : '' ?>"
                               href="index.php?page=<?= urlencode($currentPage) ?>&search=<?= urlencode($search) ?>&filter_status=all">Tous
                                les profils</a></li>

                        <li><a class="dropdown-item <?= $filterStatus === 'active' ? 'active' : '' ?>"
                               href="index.php?page=<?= urlencode($currentPage) ?>&search=<?= urlencode($search) ?>&filter_status=active">Profils
                                Actifs</a></li>

                        <li><a class="dropdown-item <?= $filterStatus === 'inactive' ? 'active' : '' ?>"
                               href="index.php?page=<?= urlencode($currentPage) ?>&search=<?= urlencode($search) ?>&filter_status=inactive">Profils
                                Inactifs</a></li>
                    </ul>
                </div>
            </div>

            <?php if (!empty($search)): ?>
                <div class="mt-2 text-center">
                    <a href="index.php?page=profiles-list&filter_status=<?= htmlspecialchars($filterStatus) ?>"
                       class="text-decoration-none text-muted small">
                        <i class="bi bi-x-circle"></i> Effacer la recherche
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?= $message ?>

    <div class="row g-4">
        <?php foreach ($users as $user): ?>
            <?php
            $isActive = isset($user['is_active']) ? $user['is_active'] : 1;
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm user-card <?= $isActive ? '' : 'inactive' ?>">

                    <span class="badge-id">#<?= $user['id'] ?></span>

                    <?php
                    $imgSrc = !empty($user['picture']) ? htmlspecialchars($user['picture']) : 'https://via.placeholder.com/300?text=Pas+d+image';
                    ?>
                    <img src="<?= $imgSrc ?>" class="card-img-top" alt="Avatar">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-center">
                            <?= htmlspecialchars($user['firstname']) ?> <?= htmlspecialchars($user['lastname']) ?>
                        </h5>

                        <p class="card-text text-center text-muted small mb-2">
                            <?= !empty($user['job_title']) ? htmlspecialchars($user['job_title']) : 'Aucun poste défini' ?>
                        </p>

                        <div class="text-center mb-3">
                            <?php if ($isActive): ?>
                                <span class="badge bg-success">Actif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactif</span>
                            <?php endif; ?>
                            <span class="badge bg-info text-dark"><?= htmlspecialchars($user['role_name'] ?? 'Role inconnu') ?></span>
                        </div>

                        <div class="mt-auto">
                            <a href="index.php?page=profile-guest&id=<?= $user['id'] ?>"
                               class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-pencil"></i> Voir / Éditer
                            </a>

                            <hr>

                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                                <button type="submit" name="action_toggle"
                                        class="btn btn-sm w-50 <?= $isActive ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                    <?= $isActive ? 'Désactiver' : 'Activer' ?>
                                </button>

                                <button type="submit" name="action_delete" class="btn btn-sm btn-outline-danger w-50"
                                        onclick="return confirm('Attention: Supprimer ce profil effacera aussi son adresse, ses expériences et ses compétences. Continuer ?');">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>