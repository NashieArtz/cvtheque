<?php
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {

    function update(PDO $pdo, $table, $columns, $new_values, $target_id)
    {
        if (count($columns) !== count($new_values)) {
            die("Erreur critique : Le nombre de colonnes ne correspond pas au nombre de valeurs.");
        }

        $set_clauses = [];
        foreach ($columns as $col) {
            $set_clauses[] = "`$col` = ?";
        }

        $setString = implode(', ', $set_clauses);

        $sql = "UPDATE `$table` SET $setString WHERE `id` = ?";

        $new_values[] = $target_id;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($new_values);
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erreur SQL lors de la mise à jour : " . $e->getMessage() . "</div>";
        }
    }

}
?>