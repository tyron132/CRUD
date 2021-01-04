<?php

require 'inc/functions.php';

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

reconnect_from_cookie();

if (isset($_SESSION['auth'])) {
	header('Location: account.php');
	exit();
}

if (!empty($_POST)) {
	// On ajoute les erreurs dans un tableau
	$errors = array();

	require_once 'inc/db.php';

	/* Si le nom d'utilisateur est vide ou qu'il ne contient pas les caractères "a-zA-Z0-9_" */
	if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
		$errors['username'] = "Votre pseudo n'est pas valide";
	} else {
		$req = $pdo->prepare('SELECT id FROM users WHERE username=?');
		$req->execute([$_POST['username']]);
		$user = $req->fetch();
		if ($user) {
			$errors['username'] = "Ce pseudo est déja pris";
		}
	}

	// Si l'adresse email est vide ou ne correspond pas au filtre
	if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Votre email n'est pas valide";
	} else {
		$req = $pdo->prepare('SELECT id FROM users WHERE email=?');
		$req->execute([$_POST['email']]);
		$user = $req->fetch();
		if ($user) {
			$errors['email'] = "Cet email existe déja";
		}
	}

	// Si le mot de passe est vide ou qu'il ne correspond pas à la confirmation
	if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {
		$errors['password'] = "Vous devez entrer un mot de passe valide";
	}

	if (empty($errors)) {
		$req = $pdo->prepare("INSERT INTO users SET username=?, password=?, email=?, confirmation_token=?");
		$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

		$token = str_random(64);

		$req->execute([$_POST['username'], $password, $_POST['email'], $token]);

		$user_id = $pdo->lastInsertId();
		mail($_POST['email'], 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/loginform/confirm.php?id=$user_id&token=$token");
		$_SESSION['flash']['success'] = "Un email de confirmation vous a été envoyer afin de valider votre compte";
		header('Location: login.php');
		exit();
	}

	//debug($errors);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Inscription</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<!-- A grey horizontal navbar that becomes vertical on small screens -->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" href="login.php"><i class="fa fa-sign-in"></i> Connexion</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="register.php"><i class="fa fa-user-plus"></i> Inscription</a>
		</li>
	</ul>
</nav>

<body class="my-login-page">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="form-icon">
						<span><i class="fa fa-user-plus"></i></span>
					</div>
					<div class="card fat">
						<div class="card-body">
							<h4 class="card-title">Inscription</h4>

							<?php if (!empty($errors)) : ?>
								<div class="alert alert-danger">
									<p>Vous n'avez pas rempli le formulaire correctement</p>
									<?php foreach ($errors as $error) : ?>
										<ul>
											<li><?= $error; ?></li>
										</ul>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<form action="" method="POST">
								<div class="form-group">
									<label for="">Nom d'utilisateur</label>
									<input type="text" name="username" class="form-control">
								</div>

								<div class="form-group">
									<label for="">Email</label>
									<input name="email" type="email" class="form-control">
								</div>

								<div class="form-group">
									<label for="">Mot de passe</label>
									<input name="password" type="password" class="form-control">
								</div>

								<div class="form-group">
									<label for="">Confirmez votre mot de passe</label>
									<input name="password_confirm" type="password" class="form-control">
								</div>

								<div class="form-group m-0">
									<button type="submit" class="btn btn-primary btn-block">
										S'inscrire
									</button>
								</div>
								<div class="mt-4 text-center">
									Vous avez déja un compte? <a href="login.php">Connexion</a>
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
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
	</script>
</body>

</html>