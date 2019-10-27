<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedinAdmin'])) {
	header('Location: index.php');
	exit();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Gestão Sinistros</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Gestão Sinistros</h1>
				<a href="home.php"><i class="fas fa-home"></i>Home</a>
				<a href="distritos.php"><i class="fas fa-city"></i>Distritos</a>
				<a href="concelhos.php"><i class="fas fa-university"></i>Concelhos</a>
				<a href="sinistros.php"><i class="fas fa-car-crash"></i>Sinistros</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i><?=$_SESSION['username']?></a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2><span id="datetime"></span></h2>

			<script>
				var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
				var today  = new Date();
				document.getElementById("datetime").innerHTML = today.toLocaleDateString("pt-PT", options);
			</script>
	
			<p>Bem-vind@, <?=$_SESSION['username']?>!</p>
		</div>
	</body>
</html>