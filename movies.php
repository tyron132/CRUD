<?php

require 'inc/functions.php';

logged_only();

// Récupère la liste des films
try {
    require_once 'inc/db.php';

    $sql = "SELECT * FROM movies";
    $stmt = $pdo->query($sql);
    if ($stmt === false) {
        die("Erreur");
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    exit();
}

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
                    <a class="nav-link active" href="movies.php"><i class="fa fa-film"></i> Films</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="submit.php"><i class="fa fa-plus"></i> Ajouter</a>
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
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Titre</th>
                <th scope="col">Description</th>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>

        <?php if (isset($_SESSION['flash'])) : ?>
            <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
                <div class="alert alert-<?= $type; ?>">
                    <?= $message; ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <tbody>

            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>

                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['synopsis']); ?></td>
                    <td><?php echo htmlspecialchars($row['id_user']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <?php if ($row['id_user'] === $_SESSION['auth']->id) : ?>
                        <td><a href="update.php?id=<?php echo $row['id'] ?>" class="test btn btn-primary btn-lg active" role="button" aria-pressed="true">Modifier</a></td>
                        <!-- <td><a href="delete.php?id=<?php echo $row['id'] ?>" class=" btn-supr btn btn-secondary btn-lg active" role="button" aria-pressed="true">Supprimer</a></td> -->
                        <td><a href="remove.php?id=<?php echo $row['id'] ?>" class=" btn-supr btn btn-secondary btn-lg active" role="button" aria-pressed="true">Supprimer</a></td>

                    <?php endif; ?>
                </tr>

            <?php endwhile; ?>
            <?php if (isset($_SESSION['flash'])) : ?>
                <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
                    <div class="alert alert-<?= $type; ?>">
                        <?= $message; ?>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

        </tbody>
    </table>

    <div class="footer">
        Copyright &copy; 2020 &mdash; Czich Tyron
    </div>

    <script>
        const deleteBtn = document.querySelectorAll(".btn-supr");
        deleteBtn.forEach(btn => {
            btn.addEventListener("click", e => {
                e.preventDefault();
                //console.log(btn.getAttribute("href"));
                fetch(btn.getAttribute("href"))
                    //.then(res => res.json())
                    .then(data => {
                        btn.closest("tr").remove();
                        //console.log(data);
                    });

            })
        })
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>