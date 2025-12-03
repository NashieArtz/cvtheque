<?php
// On suppose que la connexion $pdo est incluse via index.php

// On récupère l'ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_SESSION['user']['id'])) {
    $id = $_SESSION['user']['id'];
} else {
    header('Location: index.php');
    exit();
}

// --- 0. TRAITEMENT DE L'EDITION RAPIDE (Quick Edit) ---
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_quick_update'])) {
    // Sécurité : On vérifie que c'est bien l'utilisateur connecté qui modifie SON profil
    if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $id) {

        $newJob = htmlspecialchars($_POST['job_title']);
        $newPhone = htmlspecialchars($_POST['phone']);
        // Ajoute d'autres champs ici si tu veux (ex: description)

        $stmtUpdate = $pdo->prepare("UPDATE user SET job_title = ?, phone = ? WHERE id = ?");
        if($stmtUpdate->execute([$newJob, $newPhone, $id])) {
            $msg = "<div class='alert alert-success'>Informations mises à jour !</div>";
        }
    }
}

// 1. Récupération des infos USER (Après l'update pour avoir les données fraîches)
$stmt = $pdo->prepare("SELECT * FROM `user` WHERE `id` = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) { die("Utilisateur introuvable."); }

// 2. Récupération des EXPÉRIENCES
$stmt2 = $pdo->prepare("SELECT * FROM `experience` WHERE `user_id` = :id ORDER BY date_start DESC");
$stmt2->execute(['id' => $id]);
$experiences = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// 3. Récupération de l'ÉDUCATION
$stmt3 = $pdo->prepare("SELECT e.* FROM education e JOIN user_has_education uhe ON e.id = uhe.education_id WHERE uhe.user_id = :id ORDER BY e.date_start DESC");
$stmt3->execute(['id' => $id]);
$educations = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// 4. Récupération des SKILLS
$stmt5 = $pdo->prepare("SELECT s.* FROM skills s JOIN user_has_skills uhs ON s.id = uhs.skills_id WHERE uhs.user_id = :id");
$stmt5->execute(['id' => $id]);
$skills = $stmt5->fetchAll(PDO::FETCH_ASSOC);

// Vérification : Est-ce le propriétaire ?
$isOwner = (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $id);
?>

<style>
    /* ... Ton CSS existant ... */
    #doc-target { font-family: 'Segoe UI', sans-serif; max-width: 800px; margin: 0 auto; box-shadow: 0 0 15px rgba(0,0,0,0.1); background: white; }
    .cv-row { display: flex; flex-wrap: wrap; }
    .cv-wrap-main { width: 65%; padding: 40px; background-color: #fff; }
    .cv-wrap-sidebar { width: 35%; padding: 40px 20px; background-color: #f4f4f4; border-left: 1px solid #ddd; text-align: center; }
    .cv-name { font-size: 2.5rem; font-weight: 700; text-transform: uppercase; color: #2c3e50; line-height: 1.2; }
    .cv-subname { font-size: 1.2rem; color: #007BFF; margin-bottom: 30px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; }
    .head-title { font-size: 1.1rem; font-weight: bold; text-transform: uppercase; border-bottom: 2px solid #007BFF; padding-bottom: 5px; margin-bottom: 20px; margin-top: 30px; color: #2c3e50; }
    .cv-content-item { margin-bottom: 20px; }
    .title { font-weight: bold; font-size: 1rem; }
    .subtitle { font-weight: 600; color: #555; font-size: 0.9rem; }
    .time { font-size: 0.85rem; color: #888; font-style: italic; margin-bottom: 5px; }
    .exprecince { font-size: 0.9rem; text-align: justify; }
    .avatar img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
    .info p { margin: 5px 0; font-size: 0.9rem; word-break: break-all; }
    .skill-tag { display: inline-block; background: #e1e8ef; color: #2c3e50; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; margin: 2px; }
    @media print { #container, .edit-btn-wrapper { display: none !important; } }

    /* Style pour le bouton d'édition rapide */
    .edit-btn-wrapper { display: inline-block; margin-left: 10px; vertical-align: middle; }
    .btn-quick-edit { font-size: 0.8rem; padding: 2px 8px; border-radius: 20px; }
</style>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function generatePDF() {
        const element = document.getElementById('doc-target');
        // On cache temporairement le bouton d'edit pour le PDF s'il est visible via JS (normalement géré par CSS @media print)
        const opt = {
            margin:       0,
            filename:     'CV_<?= htmlspecialchars($user['lastname']) ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>