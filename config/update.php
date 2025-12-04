<?php
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    function update(PDO $pdo, $table, $column, $new_value)
    {
        if (isset($_POST) && !empty($_POST)) {
            $col = ' ';
            $value = ' ';
            $user_id = ($_SESSION['user']['id']);
            foreach ($column as $col) {
                $col += "`$col`, ";
                $value += '?, ';
            }
            $sql = "UPDATE `$table`(`$col`) SET VALUE ($value) WHERE `id` = $user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($new_value);

        }
    }
};


?>