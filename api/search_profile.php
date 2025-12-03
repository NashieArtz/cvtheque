<?php
include '../config/db.php';

$search = $_GET['q'] ?? ''; // 'q' pour coller à ta logique livesearch
$sort = $_GET['sort'] ?? 'newest';

// Requête de base
$sql = "SELECT user.id AS user_id, user.firstname, user.lastname, user.job_title, user.picture, 
        address.city, skills.hard_skills 
        FROM `user`
        LEFT JOIN `user_has_skills` ON user_has_skills.user_id = user.id
        LEFT JOIN `skills` ON user_has_skills.skills_id = skills.id
        LEFT JOIN `address` ON address.user_id = user.id
        WHERE 1=1";

$params = [];

// Filtre de recherche
if (!empty($search)) {
    $sql .= " AND (user.firstname LIKE ? OR user.lastname LIKE ? OR skills.hard_skills LIKE ? OR address.city LIKE ?)";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
}

// Tri
switch ($sort) {
    case 'az': $sql .= " ORDER BY user.lastname ASC"; break;
    case 'za': $sql .= " ORDER BY user.lastname DESC"; break;
    default:   $sql .= " ORDER BY user.id DESC"; break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrégation (pour éviter les doublons de cartes si multiples compétences)
$users = [];
foreach ($results as $row) {
    $uid = $row['user_id'];
    if (!isset($users[$uid])) {
        $users[$uid] = $row;
        $users[$uid]['skills'] = [];
    }
    if (!empty($row['hard_skills']) && !in_array($row['hard_skills'], $users[$uid]['skills'])) {
        $users[$uid]['skills'][] = $row['hard_skills'];
    }
}

// Génération du HTML
if (empty($users)) {
    echo '<div class="col-12 text-center py-5"><p>Aucun profil trouvé.</p></div>';
} else {
    foreach ($users as $user) {
        $img = !empty($user['picture']) ? htmlspecialchars($user['picture']) : "https://picsum.photos/300/300";
        ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 card-profile border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?= $img ?>" class="rounded-circle" width="60" height="60" style="object-fit:cover;">
                        <div class="ms-3">
                            <h5 class="fw-bold mb-0">
                                <?= htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) ?>
                            </h5>
                            <p class="small mb-0 opacity-75"><?= htmlspecialchars($user['job_title'] ?: 'Postulant') ?></p>
                            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($user['city'] ?: 'N/A') ?></small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <?php
                        $skills = array_slice($user['skills'], 0, 3);
                        foreach($skills as $skill): ?>
                            <span class="badge badge-skill me-1 mb-1"><?= htmlspecialchars($skill) ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top" style="border-color: rgba(0,0,0,0.05) !important;">
                        <a href="?page=profile-guest&id=<?= $user['user_id'] ?>" class="btn btn-sm btn-color-primary rounded-pill px-3">
                            Voir Profil
                        </a>
                        <button class="btn btn-favorite" onclick="this.classList.toggle('active')">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>