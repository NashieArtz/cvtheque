<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {

    // --- 1. Fonction pour mettre à jour les infos simples (table 'user') ---
    function update(PDO $pdo, $table, $columns, $values, $id)
    {
        if (empty($columns) || empty($values)) {
            return;
        }

        $setClauses = [];
        foreach ($columns as $col) {
            $setClauses[] = "`$col` = ?";
        }
        $setString = implode(', ', $setClauses);

        // Syntaxe standard : UPDATE user SET email = ?, firstname = ? WHERE id = ?
        $sql = "UPDATE `$table` SET $setString WHERE `id` = ?";

        // On ajoute l'ID à la fin des valeurs pour le 'WHERE'
        $values[] = $id;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
        } catch (PDOException $e) {
            die("Erreur update profil : " . $e->getMessage());
        }
    }

    // --- 2. Fonction pour mettre à jour les compétences (tables 'skills' et 'user_has_skills') ---
    function updateSkills(PDO $pdo, $skillType)
    {
        // $skillType vaut "hard_skills", "soft_skills" ou "hobbies"
        if (isset($_POST[$skillType])) {

            $user_id = $_SESSION['user']['id'];
            $content = htmlspecialchars(trim($_POST[$skillType]));

            // Sécurité : On vérifie que le type de skill est valide par rapport aux colonnes de la BDD
            $allowed_cols = ['hard_skills', 'soft_skills', 'hobbies'];
            if (!in_array($skillType, $allowed_cols)) {
                return;
            }

            try {
                // ÉTAPE 1 : On cherche si l'utilisateur a déjà une ligne dans 'skills' pour ce type précis.
                // On fait une jointure entre skills et user_has_skills
                $sqlCheck = "SELECT s.id 
                             FROM `skills` s
                             JOIN `user_has_skills` uhs ON s.id = uhs.skills_id
                             WHERE uhs.user_id = ? 
                             AND s.$skillType IS NOT NULL 
                             AND s.$skillType != ''
                             LIMIT 1";

                $stmtCheck = $pdo->prepare($sqlCheck);
                $stmtCheck->execute([$user_id]);
                $existingRow = $stmtCheck->fetch();

                if ($existingRow) {
                    // CAS A : L'utilisateur a déjà ce type de compétence, on MET À JOUR la ligne existante
                    $skill_id = $existingRow['id'];
                    $sqlUpdate = "UPDATE `skills` SET `$skillType` = ? WHERE `id` = ?";
                    $stmtUpdate = $pdo->prepare($sqlUpdate);
                    $stmtUpdate->execute([$content, $skill_id]);

                } else {
                    // CAS B : C'est nouveau, on CRÉE une ligne dans skills et on la LIE à l'user

                    // 1. Insertion dans 'skills'
                    $sqlInsert = "INSERT INTO `skills` (`$skillType`) VALUES (?)";
                    $stmtInsert = $pdo->prepare($sqlInsert);
                    $stmtInsert->execute([$content]);
                    $new_skill_id = $pdo->lastInsertId();

                    // 2. Création du lien dans 'user_has_skills'
                    $sqlLink = "INSERT INTO `user_has_skills` (`user_id`, `skills_id`) VALUES (?, ?)";
                    $stmtLink = $pdo->prepare($sqlLink);
                    $stmtLink->execute([$user_id, $new_skill_id]);
                }

            } catch (PDOException $e) {
                die("Erreur update skills ($skillType) : " . $e->getMessage());
            }
        }
    }
}
?>