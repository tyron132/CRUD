<?php

$user_id = $_GET['id'];
$token = $_GET['token'];

require 'inc/db.php';
$req = $pdo->prepare('SELECT * FROM users WHERE  id = ?');
$req->execute([$user_id]);
$user = $req->fetch();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($user && $user->confirmation_token == $token) {

    $pdo->prepare('UPDATE users SET confirmation_token = null, confirmed_at = NOW() WHERE id = ?')->execute([$user_id]);
    $_SESSION['flash']['success'] = "Votre compte a bien été valider";
    $_SESSION['auth'] = $user;

    header('Location: account.php');

    die('Votre compte a bien été valider');
} else {
    $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
    header('Location: login.php');
}
