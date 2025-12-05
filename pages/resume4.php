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
             Télécharger en PDF
        </button>

        <?php if($isOwner): ?>
            <a href="index.php?page=profile-edit" class="btn btn-outline-secondary">
                 Édition Complète
            </a>
        <?php endif; ?>
    </div>

    <div id="doc-target">
        <div class="cv-pastel">
            <header>
                <img src="<?= $user['picture'] ?>" class="avatar">
                <h1><?= $user['firstname']." ".$user['lastname'] ?></h1>
                <p><?= $user['job_title'] ?></p>
            </header>

            <div class="grid">
                <aside>
                    <h3>Contact</h3>
                    <p><?= $user['email'] ?></p>
                    <p><?= $user['phone'] ?></p>

                    <h3>Compétences</h3>
                    <ul>
                        <?php foreach($skills as $s): ?>
                            <li><?= $s['hard_skills'] ?: $s['soft_skills'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </aside>

                <main>

                    <section>
                        <h2>Expérience</h2>
                        <?php foreach($experiences as $e): ?>
                            <div class="box">
                                <strong><?= $e['date_start']." → ".($e['date_end'] ?: "Aujourd'hui") ?></strong>
                                <h4><?= $e['jobtitle'] ?></h4>
                                <p><?= nl2br(htmlspecialchars($e['description'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </section>

                    <section>
                        <h2>Éducation</h2>
                        <?php foreach($educations as $ed): ?>
                            <div class="box">
                                <strong><?= $ed['date_start']." - ".$ed['date_end'] ?></strong>
                                <h4><?= $ed['diploma'] ?></h4>
                                <p><?= $ed['school'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>
