<?php
include './config/release.php';
include './config/update.php';

$target_id = $_SESSION['user']['id'];

if (isset($_GET['id']) && !empty($_GET['id'])) {
    if (isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] == 3) {
        $target_id = intval($_GET['id']);
    } else {
        header('Location: index.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete_picture'])) {
        $pdo->prepare("UPDATE user SET picture = NULL WHERE id = ?")->execute([$target_id]);
    }

    if (isset($_POST['delete_experience'])) {
        $expId = intval($_POST['delete_experience']);
        $pdo->prepare("DELETE FROM experience WHERE id = ? AND user_id = ?")->execute([$expId, $target_id]);
    }

    if (isset($_POST['delete_education'])) {
        $eduId = intval($_POST['delete_education']);
        $pdo->prepare("DELETE FROM user_has_education WHERE education_id = ? AND user_id = ?")->execute([$eduId, $target_id]);
        $pdo->prepare("DELETE FROM education WHERE id = ?")->execute([$eduId]);
    }

    if (isset($_POST['delete_skill'])) {
        $skillId = intval($_POST['delete_skill']);
        $pdo->prepare("DELETE FROM user_has_skills WHERE skills_id = ? AND user_id = ?")->execute([$skillId, $target_id]);
    }

    if (isset($_POST['submit-add-experience'])) {
        $job = htmlspecialchars(trim($_POST['new_exp_job']));
        $employer = htmlspecialchars(trim($_POST['new_exp_employer']));
        $start = $_POST['new_exp_start'];
        $end = !empty($_POST['new_exp_end']) ? $_POST['new_exp_end'] : null;
        $desc = htmlspecialchars(trim($_POST['new_exp_desc']));

        if (!empty($job)) {
            $sql = "INSERT INTO experience (jobtitle, employer, date_start, date_end, description, user_id) VALUES (?, ?, ?, ?, ?, ?)";
            $pdo->prepare($sql)->execute([$job, $employer, $start, $end, $desc, $target_id]);
        }
    }

    if (isset($_POST['submit-add-education'])) {
        $diploma = htmlspecialchars(trim($_POST['new_edu_diploma']));
        $school = htmlspecialchars(trim($_POST['new_edu_school']));
        $start = $_POST['new_edu_start'];
        $end = !empty($_POST['new_edu_end']) ? $_POST['new_edu_end'] : null;
        $desc = htmlspecialchars(trim($_POST['new_edu_desc']));

        if (!empty($diploma)) {
            $pdo->prepare("INSERT INTO education (current_studies, diploma, school, date_start, date_end) VALUES (?, ?, ?, ?, ?)")
                    ->execute([$desc, $diploma, $school, $start, $end]);
            $eduId = $pdo->lastInsertId();
            $pdo->prepare("INSERT INTO user_has_education (user_id, education_id) VALUES (?, ?)")->execute([$target_id, $eduId]);
        }
    }

    if (isset($_POST['submit-add-skill'])) {
        $hSkill = htmlspecialchars(trim($_POST['new_hard_skill']));
        $sSkill = htmlspecialchars(trim($_POST['new_soft_skill']));

        if (!empty($hSkill) || !empty($sSkill)) {
            $pdo->prepare("INSERT INTO skills (hard_skills, soft_skills) VALUES (?, ?)")->execute([$hSkill, $sSkill]);
            $skillId = $pdo->lastInsertId();
            $pdo->prepare("INSERT INTO user_has_skills (user_id, skills_id) VALUES (?, ?)")->execute([$target_id, $skillId]);
        }
    }

    if (isset($_POST['submit-save-all'])) {
        $email = htmlspecialchars(trim($_POST['email']));
        $firstname = htmlspecialchars(trim($_POST['firstname']));
        $lastname = htmlspecialchars(trim($_POST['lastname']));
        $job_title = htmlspecialchars(trim($_POST['job_title']));
        $username = htmlspecialchars(trim($_POST['username']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $driver_licence = isset($_POST['driver_licence']) ? 1 : 0;

        $countryName = htmlspecialchars(trim($_POST['country']));
        $city = htmlspecialchars(trim($_POST['city']));
        $area_code = htmlspecialchars(trim($_POST['area_code']));
        $street_name = htmlspecialchars(trim($_POST['street_name']));

        $country_id = null;
        if (!empty($countryName)) {
            $stmtC = $pdo->prepare("SELECT id FROM country WHERE name LIKE ?");
            $stmtC->execute([$countryName]);
            $resC = $stmtC->fetch();
            if ($resC) {
                $country_id = $resC['id'];
            } else {
                $pdo->prepare("INSERT INTO country (name) VALUES (?)")->execute([$countryName]);
                $country_id = $pdo->lastInsertId();
            }
        }

        $stmtAddr = $pdo->prepare("SELECT id FROM address WHERE user_id = ?");
        $stmtAddr->execute([$target_id]);
        if ($stmtAddr->fetch()) {
            $pdo->prepare("UPDATE address SET city = ?, area_code = ?, street_name = ? WHERE user_id = ?")
                    ->execute([$city, $area_code, $street_name, $target_id]);
        } else {
            $pdo->prepare("INSERT INTO address (city, area_code, street_name, user_id) VALUES (?, ?, ?, ?)")
                    ->execute([$city, $area_code, $street_name, $target_id]);
        }

        $user_columns = ["email", "firstname", "lastname", "job_title", "driver_licence", "username", "phone"];
        $user_values = [$email, $firstname, $lastname, $job_title, $driver_licence, $username, $phone];

        if ($country_id) {
            $user_columns[] = "country_id";
            $user_values[] = $country_id;
        }

        if (!empty($_POST['password'])) {
            $user_columns[] = "pwd";
            $user_values[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
            $user_columns[] = "picture";
            $user_values[] = file_get_contents($_FILES['picture']['tmp_name']);
        }

        update($pdo, "user", $user_columns, $user_values, $target_id);
    }
}

$sql = "SELECT u.*, a.city, a.area_code, a.street_name, c.name as country_name 
        FROM user u 
        LEFT JOIN address a ON u.id = a.user_id 
        LEFT JOIN country c ON u.country_id = c.id 
        WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$target_id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) { echo "Utilisateur introuvable"; exit(); }

$stmtExp = $pdo->prepare("SELECT * FROM experience WHERE user_id = ? ORDER BY date_start DESC");
$stmtExp->execute([$target_id]);
$experiences = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

$stmtEdu = $pdo->prepare("SELECT e.* FROM education e JOIN user_has_education uhe ON e.id = uhe.education_id WHERE uhe.user_id = ? ORDER BY date_start DESC");
$stmtEdu->execute([$target_id]);
$educations = $stmtEdu->fetchAll(PDO::FETCH_ASSOC);

$stmtSkills = $pdo->prepare("SELECT s.* FROM skills s JOIN user_has_skills uhs ON s.id = uhs.skills_id WHERE uhs.user_id = ?");
$stmtSkills->execute([$target_id]);
$skills = $stmtSkills->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modification du profil : <?= htmlspecialchars($u['username']) ?></h1>
        <a href="index.php?page=profile-guest&id=<?= $u['id'] ?>" class="btn btn-outline-secondary">Retour au profil</a>
    </div>

    <form method="post" enctype="multipart/form-data" class="row g-3">

        <div class="col-md-12">
            <h4 class="mb-3 border-bottom pb-2">Informations Générales</h4>
        </div>

        <div class="col-md-4 text-center">
            <?php $pic = !empty($u['picture']) ? $u['picture'] : 'https://via.placeholder.com/150'; ?>
            <img src="<?= $pic ?>" alt="Profil" class="img-thumbnail mb-2" style="max-height: 150px;">
            <input type="file" name="picture" class="form-control form-control-sm mb-2">

            <?php if(!empty($u['picture'])): ?>
                <button type="submit" name="delete_picture" class="btn btn-outline-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer votre photo ?');">
                    Supprimer la photo
                </button>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="firstname" class="form-control" value="<?= htmlspecialchars($u['firstname']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="lastname" class="form-control" value="<?= htmlspecialchars($u['lastname']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($u['username']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Poste visé</label>
                    <input type="text" name="job_title" class="form-control" value="<?= htmlspecialchars($u['job_title']) ?>">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($u['email']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Téléphone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($u['phone']) ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-control" placeholder="Laisser vide si inchangé">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="driver_licence" id="driver_licence" <?= $u['driver_licence'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="driver_licence">Permis de conduire</label>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <h4 class="mb-3 border-bottom pb-2">Localisation</h4>
        </div>
        <div class="col-md-6">
            <label class="form-label">Pays</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($u['country_name'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Ville</label>
            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($u['city'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Code Postal</label>
            <input type="number" name="area_code" class="form-control" value="<?= htmlspecialchars($u['area_code'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Rue</label>
            <input type="text" name="street_name" class="form-control" value="<?= htmlspecialchars($u['street_name'] ?? '') ?>">
        </div>

        <div class="col-12 mt-4">
            <input type="submit" name="submit-save-all" class="btn btn-primary w-100 py-2" value="Sauvegarder les informations principales">
        </div>
    </form>

    <div class="row mt-5">
        <div class="col-md-12">
            <h4 class="mb-3 border-bottom pb-2">Expériences Professionnelles</h4>
            <ul class="list-group mb-3">
                <?php foreach($experiences as $exp): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($exp['jobtitle']) ?></strong> chez <?= htmlspecialchars($exp['employer']) ?>
                            <br><small class="text-muted"><?= $exp['date_start'] ?> - <?= $exp['date_end'] ?></small>
                        </div>
                        <form method="post" onsubmit="return confirm('Supprimer ?');">
                            <button type="submit" name="delete_experience" value="<?= $exp['id'] ?>" class="btn btn-danger btn-sm">X</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="card card-body bg-light">
                <h6>Ajouter une expérience</h6>
                <form method="post" class="row g-2">
                    <div class="col-md-6"><input type="text" name="new_exp_job" class="form-control form-control-sm" placeholder="Poste" required></div>
                    <div class="col-md-6"><input type="text" name="new_exp_employer" class="form-control form-control-sm" placeholder="Employeur" required></div>
                    <div class="col-md-6"><input type="date" name="new_exp_start" class="form-control form-control-sm" required></div>
                    <div class="col-md-6"><input type="date" name="new_exp_end" class="form-control form-control-sm"></div>
                    <div class="col-12"><textarea name="new_exp_desc" class="form-control form-control-sm" placeholder="Description"></textarea></div>
                    <div class="col-12"><button type="submit" name="submit-add-experience" class="btn btn-success btn-sm">Ajouter</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <h4 class="mb-3 border-bottom pb-2">Formations</h4>
            <ul class="list-group mb-3">
                <?php foreach($educations as $edu): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($edu['diploma']) ?></strong> - <?= htmlspecialchars($edu['school']) ?>
                            <br><small class="text-muted"><?= $edu['date_start'] ?> - <?= $edu['date_end'] ?></small>
                        </div>
                        <form method="post" onsubmit="return confirm('Supprimer ?');">
                            <button type="submit" name="delete_education" value="<?= $edu['id'] ?>" class="btn btn-danger btn-sm">X</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="card card-body bg-light">
                <h6>Ajouter une formation</h6>
                <form method="post" class="row g-2">
                    <div class="col-md-6"><input type="text" name="new_edu_diploma" class="form-control form-control-sm" placeholder="Diplôme" required></div>
                    <div class="col-md-6"><input type="text" name="new_edu_school" class="form-control form-control-sm" placeholder="École" required></div>
                    <div class="col-md-6"><input type="date" name="new_edu_start" class="form-control form-control-sm" required></div>
                    <div class="col-md-6"><input type="date" name="new_edu_end" class="form-control form-control-sm"></div>
                    <div class="col-12"><input type="text" name="new_edu_desc" class="form-control form-control-sm" placeholder="Détails (ex: Mention)"></div>
                    <div class="col-12"><button type="submit" name="submit-add-education" class="btn btn-success btn-sm">Ajouter</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <h4 class="mb-3 border-bottom pb-2">Compétences</h4>
            <div class="mb-3">
                <?php foreach($skills as $sk): ?>
                    <?php if(!empty($sk['hard_skills'])): ?>
                        <span class="badge bg-primary p-2 me-1 mb-1">
                        <?= htmlspecialchars($sk['hard_skills']) ?>
                        <form method="post" style="display:inline;">
                            <button type="submit" name="delete_skill" value="<?= $sk['id'] ?>" class="btn btn-link text-white p-0 ms-1" style="text-decoration:none;">&times;</button>
                        </form>
                    </span>
                    <?php endif; ?>
                    <?php if(!empty($sk['soft_skills'])): ?>
                        <span class="badge bg-secondary p-2 me-1 mb-1">
                        <?= htmlspecialchars($sk['soft_skills']) ?>
                        <form method="post" style="display:inline;">
                            <button type="submit" name="delete_skill" value="<?= $sk['id'] ?>" class="btn btn-link text-white p-0 ms-1" style="text-decoration:none;">&times;</button>
                        </form>
                    </span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="card card-body bg-light">
                <h6>Ajouter une compétence</h6>
                <form method="post" class="row g-2">
                    <div class="col-md-5"><input type="text" name="new_hard_skill" class="form-control form-control-sm" placeholder="Hard Skill (ex: PHP)"></div>
                    <div class="col-md-5"><input type="text" name="new_soft_skill" class="form-control form-control-sm" placeholder="Soft Skill (ex: Gestion temps)"></div>
                    <div class="col-md-2"><button type="submit" name="submit-add-skill" class="btn btn-success btn-sm w-100">Ajouter</button></div>
                </form>
            </div>
        </div>
    </div>
</div>