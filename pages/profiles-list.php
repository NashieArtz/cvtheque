<?php
include './config/release.php';

$results = userAll($pdo);

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


<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <h2 class="mb-4">Liste de profils</h2>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-3 col-sm-2 text-center">
                                <div class="rounded-circle bg-light d-flex justify-content-center align-items-center"
                                     style="width: 70px; height: 70px; margin-top: 5px;">
                                    <i class="fas fa-user-circle fa-2x text-muted"></i>
                                </div>
                            </div>
                            <div class="col-9 col-sm-10">
                                <h5 class="card-title fw-bold mb-0">NOM PRÉNOM</h5>
                                <p class="mb-0 text-muted">Poste Visée</p>
                                <p class="text-secondary small">Ville</p>
                            </div>
                        </div>
                        <div class="row g-4">
                            <?php foreach ($users as $user): ?>
                                <div class="col-12 col-md-6">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-body">
                                            <div class="col-9 col-sm-10">
                                                <h5 class="card-title fw-bold mb-0">
                                                    <?= htmlspecialchars($user['lastname']) . ' ' . htmlspecialchars($user['firstname']) ?>
                                                </h5>
                                                <p class="mb-0 text-muted"><?= htmlspecialchars($user['job_title'] ?: 'job_title') ?></p>
                                                <p class="text-secondary small"><?= htmlspecialchars($user['city']) ?></p>
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
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
