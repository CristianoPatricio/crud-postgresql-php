<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedinAdmin']) || !isset($_SESSION['loggedinUser-CRU']) || !isset($_SESSION['loggedinUser-R'])) {
	header('Location: login.php');
	exit();
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Perfil</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body class="loggedin">
	<nav class="navtop">
		<div>
			<h1>Gestão Sinistros</h1>
			<a href="home.php"><i class="fas fa-home"></i>Home</a>
			<a href="distritos.php"><i class="fas fa-city"></i>Distritos</a>
            <a href="concelhos.php"><i class="fas fa-university"></i>Concelhos</a>
            
            <!-- Check permissions -->
			<?php if ($_SESSION['role'] == "admin") :?> <!-- ADMIN -->
				<a href="sinistros.php"><i class="fas fa-car-crash"></i>Sinistros</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i><?= $_SESSION['username'] ?></a>
			<?php else if ($_SESSION['role'] == "user_cru") :?> <!-- USER-CRU-->
				<a href="sinistros-cru.php"><i class="fas fa-car-crash"></i>Sinistros</a>
				<a href="profile-user.php"><i class="fas fa-user-circle"></i><?= $_SESSION['username'] ?></a>
			<?php else : ?> <!-- USER-R-->
				<a href="sinistros-r.php"><i class="fas fa-car-crash"></i>Sinistros</a>
				<a href="profile-user.php"><i class="fas fa-user-circle"></i><?= $_SESSION['username'] ?></a>
            <?php endif; ?>
            
			<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
		</div>
	</nav>
	<div class="content">
		<span id="msgActions"></span>
		<h2>Área Pessoal</h2>
		<div>
			<p>Detalhes da conta:</p>
			<table>
				<tr>
					<td>Username:</td>
					<td><?= $_SESSION['username'] ?></td>
				</tr>
				<tr>
					<td>Permissões:</td>
					<td><?= $_SESSION['role'] ?></td>
				</tr>
			</table>
		</div>
    </div>
</body>

</html>