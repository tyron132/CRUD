<?php
require_once 'inc/functions.php';
require_once 'inc/db.php';

logged_only();

if (isset($_GET['id'])) {
    try {
        $sql = "DELETE FROM movies WHERE id=?";
        $req = $pdo->prepare($sql);
        $req->execute([$_GET['id']]);

        //$msg = ['message' => 'sa marche'];
        //echo json_encode($msg);

    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}