<?php

require 'inc/functions.php';

reconnect_from_cookie();

if (isset($_SESSION['auth'])) {
	header('Location: account.php');
	exit();
}

if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
	require_once 'inc/db.php';
	$req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
	$req->execute(['username' => $_POST['username']]);
	$user = $req->fetch();
	if ($user == null) {
		$_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrecte';
	} elseif (password_verify($_POST['password'], $user->password)) {
		$_SESSION['auth'] = $user;
		$_SESSION['flash']['success'] = 'Vous êtes maintenant connecté';
		if ($_POST['remember']) {
			$remember_token = str_random(250);
			$pdo->prepare('UPDATE users SET remember_token = ? WHERE id = ?')->execute([$remember_token, $user->id]);
			setcookie('remember', $user->id . '==' . $remember_token . sha1($user->id . 'ratonlaveurs'), time() + 60 * 60 * 24 * 7);
		}
		header('Location: account.php');
		exit();
	} else {
		$_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrecte';
	}
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Connexion</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

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
						<span><i class="fa fa-user"></i></span>
					</div>
					<div class="card fat">
						<div class="card-body">
							<h4 class="card-title">Connexion</h4>

							<?php if (isset($_SESSION['flash'])) : ?>
								<?php foreach ($_SESSION['flash'] as $type => $message) : ?>
									<div class="alert alert-<?= $type; ?>">
										<?= $message; ?>
									</div>
								<?php endforeach; ?>
								<?php unset($_SESSION['flash']); ?>
							<?php endif; ?>

							<form action="" method="POST">
								<div class="form-group">
									<label for="">Nom d'utilisateur</label>
									<input type="text" class="form-control" name="username" autofocus>
								</div>

								<div class="form-group">
									<label for="">Mot de passe
										<a href="forgot.php" class="float-right">
											Mot de passe oublié?
										</a>
									</label>
									<input type="password" class="form-control" name="password">
								</div>

								<div class="form-group">
									<label for="">
										<input type="checkbox" id="remember" name="remember"> Se souvenir de moi
									</label>
								</div>

								<div class="form-group m-0">
									<button type="submit" class="btn btn-primary btn-block">
										Se connecter
									</button>
								</div>

								<div class="mt-4 text-center">
									Vous n'avez pas encore de compte? <a href="register.php">Inscription</a>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
	</script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
	</script>
</body>

</html>