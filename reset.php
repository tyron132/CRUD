<?php

if (isset($_GET['id']) && isset($_GET['token'])) {
    require 'inc/db.php';
    require 'inc/functions.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
    $req->execute([$_GET['id'], $_GET['token']]);
    $user = $req->fetch();
    if ($user) {
        if (!empty($_POST)) {
            if (!empty($_POST['password']) && $_POST['password'] == $_POST['password_confirm']) {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $pdo->prepare('UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL')->execute([$password]);
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['flash']['success'] = 'Votre mot de passe a bien été modifié';
                $_SESSION['auth'] = $user;
                header('Location: account.php');
                exit();
            }
        }
    } else {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash']['error'] = "Ce token n'est pas valide";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Réinitialiser mon mot de passe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body class="my-login-page">
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card-wrapper">
                    <div class="form-icon">
                        <span><i class="icon icon-lock"></i></span>
                    </div>
                    <div class="card fat">
                        <div class="card-body">
                            <h4 class="card-title">Réinitialiser mon mot de passe</h4>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="">Mot de passe</label>
                                    <input type="password" class="form-control" name="password" autofocus>
                                </div>

                                <div class="form-group">
                                    <label for="">Confirmez votre mot de passe</label>
                                    <input type="password" class="form-control" name="password_confirm" autofocus>
                                </div>

                                <div class="form-group m-0">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Réinitialiser
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="footer">
                        Copyright &copy; 2020 &mdash; Czich Tyron
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="js/my-login.js"></script>
</body>

</html>