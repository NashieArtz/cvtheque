<?php
$search = $_GET['search'] ?? '';
$filterCity = $_GET['filter_city'] ?? '';
$filterSkill = $_GET['filter_skill'] ?? '';
$filterLicense = $_GET['filter_license'] ?? 'all';
$params = [];

$sql = "SELECT u.*, a.city
FROM user u
LEFT JOIN address a ON u.id = a.user_id
WHERE u.is_active = 1";

if (!empty($filterSkill)) {
    $sql .= " JOIN user_has_skills uhs ON u.id = uhs.user_id 
              JOIN skill s ON uhs.skill_id = s.id ";
}

if ($filterLicense === 'yes') {
    $sql .= " AND u.driver_licence = 1";
}

if (!empty($filterCity)) {
    $sql .= " AND a.city LIKE :city";
    $params[':city'] = "%$filterCity%";
}

if (!empty($filterSkill)) {
    $sql .= " AND s.name LIKE :skill";
    $params[':skill'] = "%$filterSkill%";
}

if (!empty($filterSkill)) {
    $sql .= " GROUP BY u.id, a.city ORDER BY u.id DESC";
} else {
    $sql .= " ORDER BY u.id DESC";
}


if (!empty($search)) {
    $term = "%$search%";
    $sql .= " AND (u.firstname LIKE :s1 
                OR u.lastname LIKE :s2 
                OR u.username LIKE :s3
                OR u.job_title LIKE :s4 
                OR a.city LIKE :s5)";
    $params[':s1'] = $term;
    $params[':s2'] = $term;
    $params[':s3'] = $term;
    $params[':s4'] = $term;
    $params[':s5'] = $term;
}


try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erreur SQL : " . $e->getMessage() . "</div>";
    $candidates = [];
}
?>


<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">Nos Talents</h1>
        <p class="text-muted">Découvrez les profils disponibles pour vos opportunités.</p>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-10">

            <div class="d-flex align-items-center mb-3">

                <div class="flex-grow-1">
                    <form action="index.php" method="GET" class="card card-body shadow-sm border-0 p-0">
                        <input type="hidden" name="page" value="profiles-list">
                        <input type="hidden" name="filter_city" value="<?= htmlspecialchars($filterCity) ?>">
                        <input type="hidden" name="filter_skill" value="<?= htmlspecialchars($filterSkill) ?>">
                        <input type="hidden" name="filter_license" value="<?= htmlspecialchars($filterLicense) ?>">

                        <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                   placeholder="Rechercher nom, titre, ville..."
                                   value="<?= htmlspecialchars($search) ?>">
                            <button class="btn btn-primary px-4" type="submit">Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>

            <form action="index.php" method="GET" class="d-flex gap-3 align-items-center card card-body shadow-sm border-0 p-3">
                <input type="hidden" name="page" value="profiles-list">
                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

                <div class="flex-fill">
                    <label for="filter_city" class="form-label small text-muted mb-1">Ville</label>
                    <input type="text" name="filter_city" id="filter_city" class="form-control form-control-sm"
                           placeholder="Ex: Paris" value="<?= htmlspecialchars($filterCity) ?>">
                </div>

                <div class="flex-fill">
                    <label for="filter_skill" class="form-label small text-muted mb-1">Compétence</label>
                    <input type="text" name="filter_skill" id="filter_skill" class="form-control form-control-sm"
                           placeholder="Ex: PHP ou React" value="<?= htmlspecialchars($filterSkill) ?>">
                </div>

                <div style="width: 150px;">
                    <label for="filter_license" class="form-label small text-muted mb-1">Permis</label>
                    <select name="filter_license" id="filter_license" class="form-select form-select-sm">
                        <option value="all">Tous</option>
                        <option value="yes" <?= $filterLicense === 'yes' ? 'selected' : '' ?>>Avec permis</option>
                    </select>
                </div>

                <div class="pt-3">
                    <button class="btn btn-sm btn-dark" type="submit" style="margin-top: 10px;">
                        Appliquer les filtres
                    </button>
                </div>
            </form>

            <?php
            $isFilterActive = !empty($search) || !empty($filterCity) || !empty($filterSkill) || $filterLicense !== 'all';
            if ($isFilterActive):
                ?>
                <div class="mt-2 text-center">
                    <a href="index.php?page=profiles-list" class="text-decoration-none text-muted small">
                        <i class="bi bi-x-circle"></i> Effacer tous les filtres
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($candidates as $candidate): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 hover-lift">

                    <div class="position-relative text-center pt-4 pb-2 bg-light rounded-top">
                        <?php
                        $imgSrc = !empty($candidate['picture']) ? htmlspecialchars($candidate['picture']) : 'https://via.placeholder.com/150?text=Candidat';
                        ?>
                        <img src="<?= $imgSrc ?>" alt="Avatar"
                             class="rounded-circle shadow-sm"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid white;">
                    </div>

                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold mb-1">
                            <?= htmlspecialchars($candidate['firstname']) ?> <?= htmlspecialchars($candidate['lastname']) ?>
                        </h5>

                        <p class="text-primary fw-medium mb-2">
                            <?= !empty($candidate['job_title']) ? htmlspecialchars($candidate['job_title']) : 'En recherche d\'opportunité' ?>
                        </p>

                        <?php if (!empty($candidate['city'])): ?>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($candidate['city']) ?>
                            </p>
                        <?php else: ?>
                            <p class="text-muted small mb-3">&nbsp;</p> <?php endif; ?>

                        <hr class="my-3 opacity-25">

                        <div class="d-grid gap-2">
                            <a href="index.php?page=profile-guest&id=<?= $candidate['id'] ?>"
                               class="btn btn-outline-primary">
                                <i class="bi bi-person-badge"></i> Voir le Profil
                            </a>

                            <a href="index.php?page=resume&id=<?= $candidate['id'] ?>" class="btn btn-dark">
                                <i class="bi bi-file-earmark-text"></i> Consulter le CV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($candidates)): ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                </div>
                <h3 class="text-muted">Aucun profil trouvé.</h3>
                <p>Essayez de modifier vos critères de recherche.</p>
                <a href="index.php?page=profiles-list" class="btn btn-outline-secondary mt-2">Voir tous les profils</a>
            </div>
        <?php endif; ?>
    </div>
</div>