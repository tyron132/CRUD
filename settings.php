<?php

require 'inc/functions.php';

logged_only();

if (!empty($_POST)) {
    if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {
        $_SESSION['flash']['danger'] = "Les mots de passes ne correspondent pas";
    } else {
        $user_id = $_SESSION['auth']->id;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        require_once 'inc/db.php';
        $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$password, $user_id]);
        $_SESSION['flash']['success'] = "Votre mot de passe a bien été mis à jour";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Paramètres du compte</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['auth'])) : ?>

                <li class="nav-item">
                    <a class="nav-link" href="account.php"><i class="fa fa-home"></i> Accueil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="movies.php"><i class="fa fa-film"></i> Films</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="submit.php"><i class="fa fa-plus"></i> Ajouter</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="settings.php"><i class="fa fa-cog"></i> Paramètres du compte</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Se déconnecter</a>
                </li>

            <?php else : ?>

                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php"><i class="fa fa-sign-in"></i> Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php"><i class="fa fa-user-plus"></i> Inscription</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="section1">
        <div class="container">
            <div class="row">
                <div class="col-sm">

                </div>
                <div class="col-sm">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="">Modifier votre mot de passe</label>

                            <input class="form-control" type="password" name="password" placeholder="Nouveau mot de passe" />
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="password_confirm" placeholder="Confirmation du mot de passe" />
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">Modifier</button>

                        <?php if (isset($_SESSION['flash'])) : ?>
                            <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
                                <div class="alert alert-<?= $type; ?>">
                                    <?= $message; ?>
                                </div>
                            <?php endforeach; ?>
                            <?php unset($_SESSION['flash']); ?>
                        <?php endif; ?>

                    </form>
                </div>
                <div class="col-sm">

                </div>
            </div>
        </div>
    </div>



    <div class="footer">
        Copyright &copy; 2020 &mdash; Czich Tyron
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>