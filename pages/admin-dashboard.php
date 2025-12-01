<?php

include './config/roles/admin.php';


?>
<div class="container py-4">

    <div class="row mb-4 align-items-center">

        <div class="col-12 col-md-auto d-flex gap-2 mb-3 mb-md-0">
            <button class="btn btn-sm text-white" style="background-color: #613F75;">
                <i class="fas fa-sort me-1"></i> Sort
            </button>
            <button class="btn btn-sm text-white" style="background-color: #613F75;">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>

        <form method="get" class="d-flex mb-3">
            <input type="search" name="s" class="form-control" placeholder="Rechercher un utilisateur..." value="<?= $_GET['s'] ?? '' ?>">
            <button class="btn btn-primary ms-2" type="submit">Rechercher</button>
        </form>
    </div>

    <h2 class="mb-4">Profiles Étudiants</h2>

    <?php
    // Récupérer tous les utilisateurs avec leurs compétences
    $stmt = $pdo->prepare("
    SELECT u.id AS user_id, u.firstname, u.lastname, u.picture, a.city, u.job_title, s.hard_skills
    FROM `user` u
    LEFT JOIN user_has_skills uhs ON u.id = uhs.user_id
    LEFT JOIN skills s ON uhs.skills_id = s.id
    LEFT JOIN address a ON a.user_id = u.id
    ORDER BY u.id
");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Regrouper les compétences par utilisateur en évitant les doublons
    $users = [];
    foreach ($results as $row) {
        $user_id = $row['user_id'];
        if (!isset($users[$user_id])) {
            $users[$user_id] = [
                    'id' => $user_id,
                    'firstname' => $row['firstname'] ?? '',
                    'lastname' => $row['lastname'] ?? '',
                    'job_title' => $row['job_title'] ?? '',
                    'picture' => $row['picture'] ?? '',
                    'city' => $row['city'] ?? 'Ville',
                    'skills' => []

            ];
        }
        if (!empty($row['hard_skills']) && !in_array($row['hard_skills'], $users[$user_id]['skills'])) {
            $users[$user_id]['skills'][] = $row['hard_skills'];
        }
    }
    ?>

    <div class="row g-4">
        <?php foreach ($users as $user): ?>
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-3 col-sm-2 text-center">
                                <div class="rounded-circle bg-light d-flex justify-content-center align-items-center"
                                     style="width: 70px; height: 70px; margin-top: 5px;">
                                    <?php if (!empty($user['picture'])): ?>
                                        <img src="<?= htmlspecialchars($user['picture']) ?>" class="rounded-circle" style="width:70px; height:70px; object-fit:cover;"/>
                                    <?php else: ?>
                                        <i class="fas fa-user-circle fa-2x text-muted"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-9 col-sm-10">
                                <h5 class="card-title fw-bold mb-0">
                                    <?= htmlspecialchars($user['lastname']) . ' ' . htmlspecialchars($user['firstname']) ?>
                                </h5>
                                <p class="mb-0 text-muted"><?= htmlspecialchars($user['job_title'] ?: 'job_title') ?></p>
                                <p class="text-secondary small"><?= htmlspecialchars($user['city']) ?></p>
                            </div>
                        </div>

                        <h6 class="mt-3 fw-bold">Compétences</h6>
                        <ul class="list-unstyled small ps-3">
                            <?php if (!empty($user['skills'])): ?>
                                <?php foreach ($user['skills'] as $skill): ?>
                                    <li>• <?= htmlspecialchars($skill) ?></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>• Aucune compétence</li>
                            <?php endif; ?>
                        </ul>

                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <a href="?page=profile&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-secondary">Editer profil</a>
                            <button class="btn bg-danger btn-sm" style="color: white;">
                                <i class="far fa-star me-1"></i> supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
