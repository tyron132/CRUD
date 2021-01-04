<?php

if(!empty($_POST) && !empty($_POST['email'])){
    require_once 'inc/db.php';
    require_once 'inc/functions.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if($user){
        session_start();
        $reset_token = str_random(64);
        $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?')->execute([$reset_token, $user->id]);
        $_SESSION['flash']['success'] = 'Les instructions du rappel de mot de passe vous ont été envoyées par emails';
        mail($_POST['email'], 'Réinitiatilisation de votre mot de passe', "Afin de réinitialiser votre mot de passe merci de cliquer sur ce lien\n\nhttp://local.dev/Lab/Comptes/reset.php?id={$user->id}&token=$reset_token");
        header('Location: login.php');
        exit();
    }else{
        $_SESSION['flash']['danger'] = 'Aucun compte ne correspond à cet adresse';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['auth'])):?>

            <li class="nav-item">
                <a class="nav-link active" href="account.php"><i class="fa fa-home"></i> Accueil</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="movies.php"><i class="fa fa-film"></i> Films</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="movies.php"><i class="fa fa-plus"></i> Ajouter</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Paramètres du compte</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Se déconnecter</a>
            </li>

            <?php else:?>

            <li class="nav-item">
                <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php"><i class="fa fa-sign-in"></i> Connexion</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.php"><i class="fa fa-user-plus"></i> Inscription</a>
            </li>
            <?php endif;?>
        </ul>
	</nav>
	
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
                            <h4 class="card-title">Mot de passe oublié</h4>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" name="email" autofocus>
                                    <div class="form-text text-muted">
                                        En cliquant sur Réinitialiser nous vous enverrons un lien de réinitialisation du
                                        mot de passe
                                    </div>
                                </div>

                                <div class="form-group m-0">

                                    <?php if(isset($_SESSION['flash'])): ?>
                                    <?php foreach($_SESSION['flash'] as $type=> $message): ?>
                                    <div class="alert alert-<?= $type; ?>">
                                        <?= $message; ?>
                                    </div>
                                    <?php endforeach;?>
                                    <?php unset($_SESSION['flash']); ?>
                                    <?php endif; ?>

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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="js/my-login.js"></script>
</body>

</html>