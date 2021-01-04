<?php
require_once 'inc/functions.php';
require_once 'inc/db.php';

logged_only();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mon compte</title>
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
                    <a class="nav-link active" href="movies.php"><i class="fa fa-plus"></i> Ajouter</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Paramètres du compte</a>
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

    <?php

    if (!empty($_POST)) {
        try {
            $sql = "DELETE FROM movies WHERE id=?";
            $req = $pdo->prepare($sql);
            $req->execute([$_GET['id']]);
            header('Location: movies.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }
    try {
        $sql = "SELECT * FROM movies WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['id']]);
        $film = $stmt->fetch();

        var_dump($film);

        if ($stmt === false) {
            die("Erreur");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }

    if ($film && $film->id_user === $_SESSION['auth']->id) {

    ?>

        <div class="container">
            <div class="row">
                <div class="col-sm-4 mx-auto">


                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="titre">Titre</label>
                            <input type="text" readonly value="<?php echo $film->title ?>" name="title" id="title" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="synopsis">Synopsis</label>
                            <textarea class="form-control" readonly id="synopsis" rows="3" name="synopsis"><?php echo $film->synopsis ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Supprimer</button>
                    </form>


                </div>

            </div>
        </div>

    <?php
    } else {
        echo "Vous n'avez pas accès à cette page!";
    }
    ?>

    <div class="footer">
        Copyright &copy; 2020 &mdash; Czich Tyron
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>