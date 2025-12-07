<?php
// On utilise GROUP_CONCAT pour récupérer toutes les compétences d'un coup dans une seule colonne "skills_list"
$sql = "SELECT u.*, a.city, 
        GROUP_CONCAT(DISTINCT s.hard_skills SEPARATOR ',') as skills_list
        FROM user u
        LEFT JOIN address a ON u.id = a.user_id
        LEFT JOIN user_has_skills uhs ON u.id = uhs.user_id 
        LEFT JOIN skills s ON uhs.skills_id = s.id
        WHERE u.is_active = 1";


// Récupération des paramètres envoyés en GET
$search = $_GET['search'] ?? '';
$filterCity = $_GET['filter_city'] ?? '';
$filterSkill = $_GET['filter_skill'] ?? '';
$filterLicense = $_GET['filter_license'] ?? 'all';
$params = [];
// Filtres
if ($filterLicense === 'yes') {
    $sql .= " AND u.driver_licence = 1";
}
if (!empty($filterCity)) {
    $sql .= " AND a.city LIKE :city";
    $params[':city'] = "%$filterCity%";
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
$sql .= " GROUP BY u.id";
if (!empty($filterSkill)) {
    $sql .= " HAVING skills_list LIKE :skill";
    $params[':skill'] = "%$filterSkill%";
}
$sql .= " ORDER BY u.id DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erreur SQL : " . $e->getMessage() . "</div>";
    $users = [];
}

$isFilterActive = !empty($search) || !empty($filterCity) || !empty($filterSkill) || $filterLicense !== 'all';
?>

<div class="container py-5">

    <h3 class="fw-bold mb-4">Liste de profils</h3>

    <div class="row justify-content-center mb-5">
        <div class="col-md-12">

            <form action="index.php" method="GET" class="search-bar-container">
                <input type="hidden" name="page" value="profiles-list">

                <div class="d-flex flex-wrap gap-3 align-items-center search-bar-div">

                    <button class="btn btn-purple px-4 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                        Filter
                    </button>

                    <div class="flex-grow-1 position-relative">
                        <input type="text" name="search" class="form-control input-search-custom"
                               placeholder="Rechercher par mot-clé (Nom, Titre, Ville...)"
                               value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn search-btn">
                            Rechercher
                        </button>
                    </div>
                </div>

                <div class="collapse mt-3 <?= ($filterCity || $filterSkill || $filterLicense !== 'all') ? 'show' : '' ?>" id="filtersCollapse">
                    <div class="row g-3 pt-2">
                        <div class="col-md-3">
                            <input type="text" name="filter_city" class="form-control" placeholder="Ville" value="<?= htmlspecialchars($filterCity) ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="filter_skill" class="form-control" placeholder="Compétence (ex: PHP)" value="<?= htmlspecialchars($filterSkill) ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="filter_license" class="form-select">
                                <option value="all">Permis : Indifférent</option>
                                <option value="yes" <?= $filterLicense === 'yes' ? 'selected' : '' ?>>Permis B Requis</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-dark w-100">Appliquer</button>
                        </div>
                    </div>

                    <?php if ($isFilterActive): ?>
                        <div class="mt-2 text-end">
                            <a href="index.php?page=profiles-list" class="text-decoration-none text-muted small">
                                 Réinitialiser la recherche
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>

        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($users as $u): ?>
            <div class="col-md-4">
                <div class="card-profile-horizontal">

                    <div class="profile-header">
                        <div class="profile-nanimg-container">
                            <?php
                            $imgSrc = !empty($u['picture']) ? htmlspecialchars($u['picture']) : 'https://via.placeholder.com/150?text=IMG';
                            ?>
                            <img src="<?= $imgSrc ?>" alt="Avatar" class="profile-img-left">
                        </div>

                        <div class="profile-info">
                            <h5><?= htmlspecialchars($u['lastname']) ?> <?= htmlspecialchars($u['firstname']) ?></h5>
                            <div class="profile-job">
                                <?= !empty($u['job_title']) ? htmlspecialchars($u['job_title']) : 'Poste Visé' ?>
                            </div>
                            <div class="profile-location">
                                <?= !empty($u['city']) ? htmlspecialchars($u['city']) : 'Ville non renseignée' ?>
                            </div>
                        </div>
                    </div>

                    <div class="skills-section">
                        <div class="skills-title">Compétences</div>
                        <ul class="skills-list">
                            <?php
                            if (!empty($u['skills_list'])) {
                                $skills = explode(',', $u['skills_list']);
                                $skillsToShow = array_slice($skills, 0, 3);
                                foreach ($skillsToShow as $skillName) {
                                    echo "<li>" . htmlspecialchars(trim($skillName)) . "</li>";
                                }
                                if (count($skills) > 3) {
                                    echo "<li style='list-style: none; color: #888;'>... et " . (count($skills) - 3) . " autres</li>";
                                }
                            } else {
                                echo "<li style='list-style: none; font-style: italic; color: #999;'>Aucune compétence listée</li>";
                            }
                            ?>
                        </ul>
                    </div>

                    <!-- Footer : Actions -->
                    <div class="card-footer-custom">
                        <a href="index.php?page=resume&id=<?= $u['id'] ?>" class="link-cv">
                            Voir CV
                        </a>
                        <a href="index.php?page=profile-guest&id=<?= $u['id'] ?>" class="btn-view-profile">
                             Voir Profil
                        </a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($users)): ?>
            <div class="col-12 text-center py-5">
                <h3 class="text-muted">Aucun profil trouvé.</h3>
                <p>Essayez de modifier vos critères.</p>
                <a href="index.php?page=profiles-list" class="btn btn-outline-secondary">Tout effacer</a>
            </div>
        <?php endif; ?>
    </div>
</div>