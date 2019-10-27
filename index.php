<?php 
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Gestão Sinistros - Login</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body style="background-image: url('background.jpg'); background-repeat: no-repeat; background-position:center; background-size: 100%;">	
		<div class="login">
			<h1>Gestão Sinistros - Login</h1>
			<form action="authenticate.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<span id="errorMessage"></span>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
	<script>
		// if the session variable === 'failed' then show div with error message
		if ("<?php echo $_SESSION['loggedinAdmin']; ?>" === "failed") {
			document.querySelector("#errorMessage").innerHTML = '<div class="alert alert-danger" role="alert" style="width: 350px; text-align:center; margin:auto;"> Incorrect username or password! </div>';
		}
	</script>
</html>