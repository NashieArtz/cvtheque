<div class="container-fluid py-4">

    <div class="row mb-4 align-items-center">

        <div class="col-12 col-md-auto d-flex gap-2 mb-3 mb-md-0">
            <button class="btn btn-sm text-white" style="background-color: #613F75;">
                <i class="fas fa-sort me-1"></i> Sort
            </button>
            <button class="btn btn-sm text-white" style="background-color: #613F75;">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>

        <div class="col-12 col-md-7">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Hinted search text" aria-label="Search">
                <button class="btn" type="button" style="background-color: #613F75; color: white;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <h2 class="mb-4">Liste des employeurs</h2>

    <div class="row g-4 d-flex flex-wrap">
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
        <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="row align-items-start">

                            <div class="col-3 col-sm-4 text-center">
                                <div class="bg-light d-flex justify-content-center align-items-center"
                                     style="width: 70px; height: 70px; margin-top: 5px;">
                                    <i class="fas fa-user-circle fa-2x text-muted"></i>
                                </div>
                            </div>

                            <div class="col-9 col-sm-10">
                                <h5 class="card-title fw-bold mb-0">NOM entreprise</h5>
                                <p class="text-secondary small">Ville</p>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button class="btn btn-sm" style="background-color: #ac748f; color: white;">
                                <i class="far fa-star me-1"></i> Voir l'entreprise
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>
<?php
