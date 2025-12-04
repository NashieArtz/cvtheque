<?php

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_SESSION['user']['id'])) {
    $id = $_SESSION['user']['id'];
} else {
    header('Location: index.php');
    exit();
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_quick_update'])) {
    if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $id) {

        $newJob = htmlspecialchars($_POST['job_title']);
        $newPhone = htmlspecialchars($_POST['phone']);

        $stmtUpdate = $pdo->prepare("UPDATE user SET job_title = ?, phone = ? WHERE id = ?");
        if($stmtUpdate->execute([$newJob, $newPhone, $id])) {
            $msg = "<div class='alert alert-success'>Informations mises à jour !</div>";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM `user` WHERE `id` = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) { die("Utilisateur introuvable."); }

$stmt2 = $pdo->prepare("SELECT * FROM `experience` WHERE `user_id` = :id ORDER BY date_start DESC");
$stmt2->execute(['id' => $id]);
$experiences = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmt3 = $pdo->prepare("SELECT e.* FROM education e JOIN user_has_education uhe ON e.id = uhe.education_id WHERE uhe.user_id = :id ORDER BY e.date_start DESC");
$stmt3->execute(['id' => $id]);
$educations = $stmt3->fetchAll(PDO::FETCH_ASSOC);

$stmt5 = $pdo->prepare("SELECT s.* FROM skills s JOIN user_has_skills uhs ON s.id = uhs.skills_id WHERE uhs.user_id = :id");
$stmt5->execute(['id' => $id]);
$skills = $stmt5->fetchAll(PDO::FETCH_ASSOC);

$isOwner = (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $id);

$pdfFilename = 'CV_' . htmlspecialchars($user['lastname']) . '_' . htmlspecialchars($user['firstname']) . '.pdf';
?>

<div class="container my-5">

    <div id="container" class="text-center mb-4">
        <?= $msg ?> <button onclick="generatePDF()" class="btn btn-primary btn-lg shadow">
            <i class="bi bi-file-earmark-pdf"></i> Télécharger en PDF
        </button>

        <?php if($isOwner): ?>
            <a href="index.php?page=profile-edit" class="btn btn-outline-secondary">
                <i class="bi bi-gear"></i> Édition Complète
            </a>
        <?php endif; ?>
    </div>

    <div id="doc-target">
        <div class="cv-row">

            <div class="cv-wrap-main">
                <div class="cv-name">
                    <?= htmlspecialchars($user['lastname']) . ' ' . htmlspecialchars($user['firstname']) ?>
                </div>

                <div class="cv-subname">
                    <?= !empty($user['job_title']) ? htmlspecialchars($user['job_title']) : 'Candidat' ?>

                    <?php if($isOwner): ?>
                        <div class="edit-btn-wrapper">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-quick-edit" data-bs-toggle="modal" data-bs-target="#quickEditModal">
                                <i class="bi bi-pencil-fill"></i> Modifier
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="cv-content">
                    <div class="head-title">Expériences Professionnelles</div>
                    <?php if(empty($experiences)): ?><p class="text-muted">Aucune expérience.</p><?php endif; ?>
                    <?php foreach ($experiences as $experience): ?>
                        <div class="cv-content-item">
                            <div class="title"><?= htmlspecialchars($experience['jobtitle']) ?></div>
                            <div class="subtitle"><?= htmlspecialchars($experience['employer']) ?></div>
                            <div class="time"><?= htmlspecialchars($experience['date_start']) ?> - <?= $experience['date_end'] ? htmlspecialchars($experience['date_end']) : 'Présent' ?></div>
                            <div class="exprecince"><?= nl2br(htmlspecialchars($experience['description'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cv-content">
                    <div class="head-title">Formation</div>
                    <?php foreach ($educations as $edu): ?>
                        <div class="cv-content-item">
                            <div class="title"><?= htmlspecialchars($edu['diploma']) ?></div>
                            <div class="subtitle"><?= htmlspecialchars($edu['school']) ?></div>
                            <div class="time"><?= htmlspecialchars($edu['date_start']) ?> - <?= $edu['date_end'] ?></div>
                            <?php if(!empty($edu['current_studies'])): ?><div class="exprecince small"><?= htmlspecialchars($edu['current_studies']) ?></div><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="cv-wrap-sidebar">
                <div class="avatar">
                    <?php $photo = !empty($user['picture']) ? $user['picture'] : 'https://via.placeholder.com/150'; ?>
                    <img src="<?= htmlspecialchars($photo) ?>" alt="Profil" />
                </div>

                <div class="info">
                    <div class="head-title" style="margin-top:0;">Contact</div>
                    <p><a href="mailto:<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></a></p>

                    <?php if(!empty($user['phone'])): ?>
                        <p><a href="tel:<?= htmlspecialchars($user['phone']) ?>"><?= htmlspecialchars($user['phone']) ?></a></p>
                    <?php endif; ?>
                </div>

                <div class="cv-skills">
                    <div class="head-title">Hard Skills</div>
                    <div class="cv-skills-item">
                        <?php foreach ($skills as $skil): if (!empty($skil['hard_skills'])): ?>
                            <span class="skill-tag"><?= htmlspecialchars($skil['hard_skills']) ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>

                <div class="cv-skills">
                    <div class="head-title">Soft Skills</div>
                    <div class="cv-skills-item">
                        <?php foreach ($skills as $skil): if (!empty($skil['soft_skills'])): ?>
                            <span class="skill-tag"><?= htmlspecialchars($skil['soft_skills']) ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($isOwner): ?>
    <div class="modal fade" id="quickEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Personnaliser le titre du CV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action_quick_update" value="1">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Intitulé du poste (Titre du CV)</label>
                            <input type="text" name="job_title" class="form-control"
                                   value="<?= htmlspecialchars($user['job_title']) ?>" required>
                            <div class="form-text">Adaptez ce titre selon l'offre à laquelle vous postulez.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Numéro de téléphone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="<?= htmlspecialchars($user['phone']) ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
