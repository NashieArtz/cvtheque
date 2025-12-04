<?php
$search = $_GET['search'] ?? '';
$filterAvailability = $_GET['filter_availability'] ?? 'all';
$params = [];

$sql = "SELECT u.*, a.city
FROM user u
LEFT JOIN address a ON u.id = a.user_id
WHERE u.is_active = 1";
if ($filterAvailability === 'looking') {
    $sql .= " AND u.is_looking_for_job = 1";
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
        <div class="col-md-10"> <div class="d-flex gap-3 align-items-center">

                <div class="flex-grow-1">
                    <form action="index.php" method="GET" class="card card-body shadow-sm border-0 p-0">
                        <input type="hidden" name="page" value="profiles-list">
                        <input type="hidden" name="filter_availability" value="<?= htmlspecialchars($filterAvailability) ?>">

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
                    if ($filterAvailability === 'available') {
                        $buttonText = 'Statut: Disponibles';
                        $buttonClass = 'btn-success';
                    } elseif ($filterAvailability === 'employed') {
                        $buttonText = 'Statut: En Poste';
                        $buttonClass = 'btn-warning';
                    }
                    $currentPage = $_GET['page'] ?? 'profiles-list';
                    ?>
                    <button class="btn <?= $buttonClass ?> dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $buttonText ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">

                        <li><a class="dropdown-item <?= $filterAvailability === 'all' ? 'active' : '' ?>"
                               href="index.php?page=<?= urlencode($currentPage) ?>&search=<?= urlencode($search) ?>&filter_availability=all">Tous les profils</a></li>

                        <li><a class="dropdown-item <?= $filterAvailability === 'available' ? 'active' : '' ?>"
                               href="index.php?page=<?= urlencode($currentPage) ?>&search=<?= urlencode($search) ?>&filter_availability=available">Cherche activement</a></li>

                        <li><a class="dropdown-item <?= $filterAvailability === 'employed' ? 'active' : '' ?>"
                               href="index.php?page=<?= urlencode($currentPage) ?>&search=<?= urlencode($search) ?>&filter_availability=employed">Déjà en poste</a></li>
                    </ul>
                </div>
            </div>

            <?php if (!empty($search)): ?>
                <div class="mt-2 text-center">
                    <a href="index.php?page=profiles-list&filter_availability=<?= htmlspecialchars($filterAvailability) ?>" class="text-decoration-none text-muted small">
                        <i class="bi bi-x-circle"></i> Effacer la recherche
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