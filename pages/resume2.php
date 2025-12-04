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
    <div class="cv-container">
      <div class="left-column">
        <img class="portait"  src=" <?= $user['picture'] ?>" alt="photo de profil"/>
        <div class="section">
          <h2>COMPÉTENCES</h2>
            <h3>Hard skills</h3>
          <ul class="skills">
    <?php foreach ($skills as $skill): ?>
        <?php if (!empty($skill['hard_skills'])): ?>
            <li><i class="icon fas fa-check-circle text-darkblue"></i> <strong><?= htmlspecialchars($skill['hard_skills']) ?></strong></li>
        <?php endif; ?>
    <?php endforeach; ?>
        <h3>Soft skills</h3>
              <?php foreach ($skills as $skill): ?>
        <?php if (!empty($skill['soft_skills'])): ?>
            <li><i class="icon fas fa-check-circle text-darkblue"></i> <?= htmlspecialchars($skill['soft_skills']) ?></li>
        <?php endif; ?>
              <?php endforeach; ?>
</ul>
        </div>
      </div>
      <div class="right-column">
        <div class="header">
          <h1><?= htmlspecialchars($user['firstname']) ?> <span class="text-blue text-uppercase"><?= htmlspecialchars($user['lastname']) ?></span></h1>
          <p><?= !empty($user['job_title']) ? htmlspecialchars($user['job_title']) : 'Candidat' ?></p>
          <ul class="infos">
            <li><i class="icon fas fa-at text-blue"></i> <a class="a-CV2" href="mailto:<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></a></li>
            <li><i class="icon fas fa-phone text-blue"></i> <?= !empty($user['phone']) ? htmlspecialchars($user['phone']) : '' ?></li>
            <li><i class="icon fas fa-map-marker-alt text-blue"></i> <?= !empty($user['address']) ? htmlspecialchars($user['address']) : '' ?></li>
          </ul>
        </div>
        <div class="content">
          <div class="section">
            <h2>Expériences <br><span class="text-blue">professionelles</span></h2>
            <?php foreach ($experiences as $experience): ?>
                <p>
                    <strong><?= htmlspecialchars($experience['date_start']) ?>
                    <i class="fas fa-long-arrow-alt-right"></i>
                    <?= $experience['date_end'] ? htmlspecialchars($experience['date_end']) : 'Présent' ?></strong><br>
                    <em><?= htmlspecialchars($experience['jobtitle']) ?></em> — <?= htmlspecialchars($experience['employer']) ?>
                </p>
                <ul class="experience-list">
                    <li><?= nl2br(htmlspecialchars($experience['description'])) ?></li>
                </ul>
            <?php endforeach; ?>
          </div>
          <div class="section">
            <p>
              <strong>2021</strong>
              <br>
              Freelance en activité
            </p>
            <ul class="experience-list">
              <li>Freelance Front-end Developer</li>
              <li>Unity Developer / Sound Designer</li>
            </ul>
          </div>
          <div class="section">
            <h2>Études <br><span class="text-blue">& formations</span></h2>
            <?php foreach ($educations as $edu): ?>
            <p>
              <strong><?= htmlspecialchars($edu['date_start']) ?>
              <i class="fas fa-long-arrow-alt-right"></i>
              <?= htmlspecialchars($edu['date_end']) ?></strong><br>
              <em><?= htmlspecialchars($edu['diploma']) ?></em>, <?= htmlspecialchars($edu['school']) ?>
            </p>
            <?php endforeach; ?>
          </div>

        </div>
      </div>
    </div>
</div>
