<?php
function logAction($action, $username, $id) {
    $date = date("Y-m-d H:i:s");
    $line = "[$date] action=$action user=$username id=$id\n";

    file_put_contents(__DIR__ . "/append.txt", $line, FILE_APPEND);
}
?><?php
