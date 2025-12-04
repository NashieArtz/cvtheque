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
    <div class="responsive">
        <div class="CV-header">
            <div class="head-top"></div>
            <div class="head-center"></div>
            <div class="head-bottom"></div>
            <div class="head-words">
                <h3><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></h3>
                <p><?= !empty($user['job_title']) ? htmlspecialchars($user['job_title']) : 'Candidat' ?></p>
            </div>
            <div class="head-image">
                <div class="head-background">
                    <div class="head-this">
                        <img src="<?= $user['picture'] ?>" alt="photo de profil" />
                    </div>
                </div>
            </div>
        </div>

        <section class="section-CV3">
            <aside>
                <div class="profile">
                    <div class="profile-top">

                <div class="contact">
                    <div class="contact-top">
                        <div class="title">
                            <i class="fas fa-phone"></i>
                            <h3>Contact</h3>
                        </div>
                    </div>
                    <div class="contact-bottom">
                        <ul>
                            <li>
                                <div class="p-left"><p>Nom</p></div>
                                <div class="p-right"><p><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></p></div>
                            </li>
                            <li>
                                <div class="p-left"><p>Téléphone</p></div>
                                <div class="p-right"><p><?= htmlspecialchars($user['phone']) ?></p></div>
                            </li>
                            <li>
                                <div class="p-left"><p>Email</p></div>
                                <div class="p-right"><p><?= htmlspecialchars($user['email']) ?></p></div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="skills">
                    <div class="skills-top">
                        <div class="title">
                            <i class="fas fa-check-circle"></i>
                            <h3>Compétences</h3>
                        </div>
                    </div>
                    <div id="scrolls" class="skills-bottom">
                        <ul>
                            <?php foreach ($skills as $skill): ?>
                                <?php if (!empty($skill['hard_skills'])): ?>
                                    <li>
                                        <div class="skills-name"><p><?= htmlspecialchars($skill['hard_skills']) ?></p></div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </aside>

            <article>
                <div class="education">
                    <div class="education-top">
                        <div class="icon-left"><i class="fas fa-graduation-cap"></i></div>
                        <div class="title-right"><h3>Éducation</h3></div>
                    </div>
                    <div class="education-bottom">
                        <div id="scrolls" class="history">
                            <?php foreach ($educations as $edu): ?>
                                <div class="history-parts">
                                    <p><?= htmlspecialchars($edu['date_start']) ?> - <?= htmlspecialchars($edu['date_end']) ?></p>
                                    <h3><?= htmlspecialchars($edu['diploma']) ?></h3>
                                    <p><?= htmlspecialchars($edu['school']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="experience">
                    <div class="experience-top">
                        <div class="icon-left"><i class="fas fa-briefcase"></i></div>
                        <div class="title-right"><h3>Expérience</h3></div>
                    </div>
                    <div class="experience-bottom">
                        <div id="scrolls" class="history">
                            <?php foreach ($experiences as $experience): ?>
                                <div class="history-parts">
                                    <p><?= htmlspecialchars($experience['date_start']) ?> - <?= htmlspecialchars($experience['date_end'] ?? "Présent") ?></p>
                                    <h3><?= htmlspecialchars($experience['jobtitle']) ?></h3>
                                    <p><?= nl2br(htmlspecialchars($experience['description'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="languages">
                    <div class="languages-top">
                        <div class="icon-left"><i class="fas fa-language"></i></div>
                        <div class="title-right"><h3>Soft skills</h3></div>
                    </div>
                    <div id="scrolls" class="languages-bottom">
                        <ul>
                            <?php foreach ($skills as $skill): ?>
                                <?php if (!empty($skill['soft_skills'])): ?>
                                    <li>
                                        <div class="languages-name"><p><?= htmlspecialchars($skill['soft_skills']) ?></p></div>
                                        <div class="languages-line"><div class="languages-line-this"></div></div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </article>
        </section>
    </div>
</div>
        <!--Add External Libraries - JQuery and jspdf
        check out url - https://scotch.io/@nagasaiaytha/generate-pdf-from-html-using-jquery-and-jspdf
        -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="./assets/js/resume.js"></script>
