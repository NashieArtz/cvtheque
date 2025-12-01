<?php
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']["id"])) {
    die("Utilisateur non connecté.");
}

$id = $_SESSION['user']["id"];

// Requête préparée pour éviter les injections SQL
$stmt = $pdo->prepare("SELECT * FROM `user` WHERE `id` = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}
$stmt2 = $pdo->prepare("SELECT * FROM `experience` WHERE `user_id` = :id");
$stmt2->execute(['id' => $id]);
$experiences = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$stmt3 = $pdo->prepare("SELECT education_id FROM `user_has_education` WHERE `user_id` = :id");
$stmt3->execute(['id' => $id]);
$userEducationLinks = $stmt3->fetchAll(PDO::FETCH_ASSOC);
$educations = [];

foreach ($userEducationLinks as $link) {
    $stmt4 = $pdo->prepare("SELECT * FROM `education` WHERE `id` = :id");
    $stmt4->execute(['id' => $link['education_id']]);
    $edu = $stmt4->fetch(PDO::FETCH_ASSOC);

    if ($edu) {
        $educations[] = $edu;
    }
}
$stmt5 = $pdo->prepare("SELECT skills_id FROM `user_has_skills` WHERE `user_id` = :id");
$stmt5->execute(['id' => $id]);
$userSkllisLinks = $stmt5->fetchAll(PDO::FETCH_ASSOC);
$skills = [];

foreach ($userSkllisLinks as $link) {
    $stmt6 = $pdo->prepare("SELECT * FROM `skills` WHERE `id` = :id");
    $stmt6->execute(['id' => $link['skills_id']]);
    $skil = $stmt6->fetch(PDO::FETCH_ASSOC);

    if ($skil) {
        $skills[] = $skil;
    }
}
?>


<div id="doc-target">
    <div class="cv">
    <div class="cv-row">
        <div class="cv-wrap">
            <div class="cv-name"><?= htmlspecialchars($user['lastname']) . ' ' . htmlspecialchars($user['firstname']) ?></div>
            <div class="cv-subname">Fonction rechercher</div>
            <div class="cv-content">
                <div class="head-title">Expérience</div>

                <?php foreach ($experiences as $experience): ?>
                    <div class="cv-content-item">
                        <div class="title"><?= htmlspecialchars($experience['jobtitle']) ?></div>
                        <div class="subtitle"><?= htmlspecialchars($experience['employer']) ?></div>
                        <div class="time"><?= htmlspecialchars($experience['date_start']) . ' - ' . htmlspecialchars($experience['date_end']) ?></div>
                        <div class="exprecince"><?= htmlspecialchars($experience['description']) ?></div>
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="cv-content">
                <?php foreach ($educations as $edu): ?>
                    <div class="cv-content-item">
                        <div class="title"><?= htmlspecialchars($edu['current_studies']) ?></div>
                        <div class="subtitle"><?= htmlspecialchars($edu['diploma']) ?></div>
                        <div class="subtitle"><?= htmlspecialchars($edu['school']) ?></div>
                        <div class="time"><?= htmlspecialchars($edu['date_start']) . ' - ' . htmlspecialchars($edu['date_end']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="cv-wrap">
            <div class="avatar">
                <img src=" <?= $user['picture'] ?>" alt="photo de profil" width="512px" height="512px"/>
            </div>
            <div class="info">
                <div class="title">Contact</div>
                <p><a href="mailto:<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></a></p>
                <p><a href="tel:<?= htmlspecialchars($user['phone']) ?>"><?= htmlspecialchars($user['phone']) ?></a></p>
                <p></p>
            </div>
            <div class="cv-skills">
                <div class="head-title">Primary Skills
                </div>
                <div class="cv-skills-item">
                        <div class="cv-content-item">
                            <?php foreach ($skills as $skil): ?>
                                <?php if (!empty($skil['hard_skills'])): ?>
                                    <div class="subtitle"><?= htmlspecialchars($skil['hard_skills']) ?></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>


            </div>
            <div class="cv-skills">
                <div class="head-title">Secondary Skills
                </div>
                <div class="cv-skills-item">
                    <div class="cv-content-item">
                        <?php foreach ($skills as $skil): ?>
                            <?php if (!empty($skil['soft_skills'])): ?>
                                <div class="subtitle"><?= htmlspecialchars($skil['soft_skills']) ?></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                </div>

            </div>
            <div class="cv-social">
                <a href="" title="Github">
                    <svg viewBox="0 0 24 24" width="30" height="30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" shape-rendering="geometricPrecision" style="">
                        <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"></path>
                    </svg>

                </a>
                <a href="#" title="Codepen">
                    <svg viewBox="0 0 24 24" width="30" height="30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" shape-rendering="geometricPrecision" style="">
                        <path d="M12 2l10 6.5v7L12 22 2 15.5v-7L12 2z"></path>
                        <path d="M12 22v-6.5"></path>
                        <path d="M22 8.5l-10 7-10-7"></path>
                        <path d="M2 15.5l10-7 10 7"></path>
                        <path d="M12 2v6.5"></path>
                    </svg></a> <a href="#" title="Linkedin">
                    <svg viewBox="0 0 24 24" width="30" height="30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" shape-rendering="geometricPrecision" style="">
                        <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"></path>
                        <path d="M2 9h4v12H2z"></path>
                        <circle cx="4" cy="4" r="2"></circle>
                    </svg></a>
                <a href="#" title="İnstagram">
                    <svg viewBox="0 0 24 24" width="30" height="30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" shape-rendering="geometricPrecision" style="">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                        <path d="M17.5 6.5h.01"></path>
                    </svg>
                </a>
                <a href="#" title="Medium">
                    <svg fill="currentColor" width="30px" height="30px" viewBox="0 0 15 15">
                        <g>
                            <path d="M1.79062 2.44688C1.80938 2.2625 1.7375 2.07812 1.6 1.95313L0.190625 0.253125L0.190625 0L4.57188 0L7.95937 7.42812L10.9375 0L15.1156 0L15.1156 0.253125L13.9094 1.40937C13.8062 1.4875 13.7531 1.61875 13.775 1.74687L13.775 10.2469C13.7531 10.375 13.8062 10.5063 13.9094 10.5844L15.0875 11.7406L15.0875 11.9937L9.15938 11.9937L9.15938 11.7406L10.3813 10.5562C10.5 10.4375 10.5 10.4 10.5 10.2188L10.5 3.35L7.10313 11.9719L6.64375 11.9719L2.69375 3.35L2.69375 9.12812C2.65937 9.37187 2.74063 9.61563 2.9125 9.79062L4.5 11.7156L4.5 11.9687L0 11.9687L0 11.7188L1.5875 9.79062C1.75625 9.61563 1.83438 9.36875 1.79062 9.12812L1.79062 2.44688Z"></path>
                        </g>
                    </svg>
                </a>

            </div>
        </div>

    </div>

</div>
</div>
    </div>
</div>

<div id="container">
    <p>

        <button onclick="generatePDF()" style="display:block;margin:20px auto;padding:10px 20px;background:#007BFF;color:#fff;border:none;border-radius:5px;cursor:pointer;">
            Télécharger en PDF
        </button>

    </p>
<!--Add External Libraries - JQuery and jspdf
check out url - https://scotch.io/@nagasaiaytha/generate-pdf-from-html-using-jquery-and-jspdf
-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./assets/js/resume.js"></script>
