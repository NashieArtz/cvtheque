<?php
$search = $_GET['search'] ?? '';
$params = [];

$sql = "SELECT u.*, a.city
FROM user u
LEFT JOIN address a ON u.id = a.user_id
WHERE u.is_active = 1";

// Ajout des conditions de recherche
if (!empty($search)) {
// Recherche sur le Nom, Prénom, Titre du job ou Ville
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


// Ordre d'affichage (les plus récents en premier ou par nom)
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En cas d'erreur SQL, on l'affiche proprement pour le débogage
    echo "<div class='alert alert-danger'>Erreur SQL : " . $e->getMessage() . "</div>";
    $candidates = []; // Tableau vide pour éviter de casser le reste de la page
}
?>


<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">Nos Talents</h1>
        <p class="text-muted">Découvrez les profils disponibles pour vos opportunités.</p>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <form action="index.php" method="GET" class="card card-body shadow-sm border-0">
                <input type="hidden" name="page" value="profiles-list">

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

            <?php if (!empty($search)): ?>
                <div class="mt-2 text-center">
                    <a href="index.php?page=profiles-list" class="text-decoration-none text-muted small">
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

<style>
    /* Petit effet CSS pour le survol des cartes */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }
</style>