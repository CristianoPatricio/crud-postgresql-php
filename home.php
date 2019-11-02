<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedinAdmin']) || !isset($_SESSION['loggedinUser-CRU']) || !isset($_SESSION['loggedinUser-R'])) {
	header('Location: login.php');
	exit();
}
/*
echo "Admin:" . $_SESSION['loggedinAdmin'];
echo "//";
echo "USER-CRU:" . $_SESSION['loggedinUser-CRU'];
echo "//";
echo "USER-R:" . $_SESSION['loggedinUser-R'];
*/
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Home</title>
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
			<?php if ($_SESSION['role'] == "admin") : ?>
				<!-- ADMIN -->
				<a href="sinistros.php"><i class="fas fa-car-crash"></i>Sinistros</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i><?= $_SESSION['username'] ?></a>
			<?php elseif ($_SESSION['role'] == "user_cru") : ?>
				<!-- USER-CRU-->
				<a href="sinistros-cru.php"><i class="fas fa-car-crash"></i>Sinistros</a>
				<a href="profile-user.php"><i class="fas fa-user-circle"></i><?= $_SESSION['username'] ?></a>
			<?php else : ?>
				<!-- USER-R-->
				<a href="sinistros-r.php"><i class="fas fa-car-crash"></i>Sinistros</a>
				<a href="profile-user.php"><i class="fas fa-user-circle"></i><?= $_SESSION['username'] ?></a>
			<?php endif; ?>

			<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
		</div>
	</nav>
	<div class="content">
		<h2><span id="datetime"></span></h2>

		<script>
			var options = {
				weekday: 'long',
				year: 'numeric',
				month: 'long',
				day: 'numeric'
			};
			var today = new Date();
			document.getElementById("datetime").innerHTML = today.toLocaleDateString("pt-PT", options);
		</script>

		<h2>Sinistralidade Rodoviária: 2004 a 2017</h2>
		<form action="home.php" method="get" style="background-color: #e3e8e5; padding: 10px; border-radius: 5px;" id="searchDataForm">
			<div class="form-row">
				<div class="form-group col-md-3">
					<i class="fas fa-city grey-text"></i>
					<label for="role">Distrito</label>
					<select class="form-control" id="selectListDistritos" name="distrito">
						<option value="">Selecione...</option>
						<?php
						// connect to database
						$conn = pg_pconnect("host=52.47.199.255 dbname=teste user=ubuntu password=1234");
						if (!$conn) {
							echo "An error occurred.\n";
							exit;
						}
						// get all the uid from the uid column in users
						$result = pg_query($conn, "SELECT id_distrito,nome_distrito FROM distritos");
						if (!$result) {
							// error message  
							echo "An error occurred.\n";
							exit;
						}
						// dispaly on screen all uid data from users
						while ($row = pg_fetch_row($result)) {
							echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
						}
						?>
					</select>
				</div>
				<div class="form-group col-md-2">
					<i class="fas fa-calendar grey-text"></i>
					<label for="role">Ano</label>
					<select class="form-control" id="selectListAno" name="ano" >
						<option value="">Selecione...</option>
						<?php
							for($i = 2004; $i <= 2020; $i++) {
								echo '<option value="' . $i . '">' . $i . '</option>';
							}
						?>
					</select>
				</div>
				<button type="submit" class="btn btn-primary"  style="height: 38px; margin-top:32px;"><i class="fas fa-search-plus"></i> Pesquisar</button>
				<button id="btnLimpar" type="button" class="btn btn-danger" style="height: 38px; margin-left: 355px;"><i class="fas fa-broom"></i> Limpar</button>
			</div>
		</form>
		<script>
			// Validação do formulário
			$('#selectListDistritos').val('<?php echo $_GET['distrito']; ?>');
			$('#selectListAno').val('<?php echo $_GET['ano']; ?>');

			var btnLimpar = document.querySelector("#btnLimpar");

				btnLimpar.addEventListener('click', function(){
					// Reset aos campos da data
					$('#selectListDistritos').val("");
					$('#selectListAno').val("");
				});
		</script>
		<?php
		// connect to database
		$conn = pg_pconnect("host=52.47.199.255 dbname=teste user=ubuntu password=1234");
		if (!$conn) {
			echo "An error occurred.\n";
			exit;
		}

		//Transação
		pg_query("BEGIN TRANSACTION ISOLATION LEVEL SERIALIZABLE") or die("Could not start transaction\n");

		// Se as variáveis de pesquisa estão definidas
		if (isset($_GET['distrito']) && $_GET['distrito'] != null){
			$id_distrito = $_GET['distrito'];
			if (isset($_GET['ano']) && $_GET['ano'] != null) {
				$ano = $_GET['ano'];
				$result = pg_query($conn, "SELECT nome_distrito, count(sinistros) AS nSinistros, SUM(mortos) AS nMortos, SUM(feridosgraves) AS nFG FROM sinistros, distritos WHERE sinistros.id_distrito = distritos.id_distrito AND datahora LIKE '%$ano%' AND distritos.id_distrito = $id_distrito  GROUP BY nome_distrito ORDER BY nome_distrito");	
			} else {
				$result = pg_query($conn, "SELECT nome_distrito, count(sinistros) AS nSinistros, SUM(mortos) AS nMortos, SUM(feridosgraves) AS nFG FROM sinistros, distritos WHERE sinistros.id_distrito = distritos.id_distrito AND distritos.id_distrito = $id_distrito GROUP BY nome_distrito ORDER BY nome_distrito");
			}
		} else if (isset($_GET['ano']) && $_GET['ano'] != null){
			$ano = $_GET['ano'];
			$result = pg_query($conn, "SELECT nome_distrito, count(sinistros) AS nSinistros, SUM(mortos) AS nMortos, SUM(feridosgraves) AS nFG FROM sinistros, distritos WHERE sinistros.id_distrito = distritos.id_distrito AND datahora LIKE '%$ano%' GROUP BY nome_distrito ORDER BY nome_distrito");
		} else if (!isset($_GET['distrito']) || !isset($_GET['ano']) || $_GET['distrito'] == null || $_GET['ano'] == null){
			$result = pg_query($conn, "SELECT nome_distrito, count(sinistros) AS nSinistros, SUM(mortos) AS nMortos, SUM(feridosgraves) AS nFG FROM sinistros, distritos WHERE sinistros.id_distrito = distritos.id_distrito GROUP BY nome_distrito ORDER BY nome_distrito");
		}

		if (!$result) {
			pg_query("ROLLBACK") or die("Transaction rollback failed\n");
			// error message  
			echo "An error occurred.\n";
			exit;
		} else {
			pg_query("COMMIT") or die("Transaction commit failed\n");
		}
		?>
		<div class='container'>
			<div class="row">
				<table class="table table-striped table-bordered" id="statisticsTable">
					<thead>
						<tr class='active'>
							<th class="align-middle">Distrito</th>
							<th class="align-middle">N.º Acidentes</th>
							<th class="align-middle">N.º Mortos</th>
							<th class="align-middle">N.º Feridos Graves</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($row = pg_fetch_row($result)) { ?>
							<tr>
								<td><?= $row[0] ?></td>
								<td class="nAcidentes"><?= $row[1] ?></td>
								<td class="nMortos"><?= $row[2] ?></td>
								<td class="nFeridosGraves"><?= $row[3] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<script language="javascript" type="text/javascript">
					// Soma os item de uma coluna
					function totalColumn(idColuna) {
						var tds = document.getElementById('statisticsTable').getElementsByTagName('td');
						var sum = 0;
						for (var i = 0; i < tds.length; i++) {
							if (tds[i].className == idColuna) {
								sum += isNaN(tds[i].innerHTML) ? 0 : parseInt(tds[i].innerHTML);
							}
						}

						return sum;
					}

					// N.º Acidentes
					var nAcidentes = totalColumn('nAcidentes');

					// N.º Mortos
					var nMortos = totalColumn('nMortos');

					// N.º Feridos Graves
					var nFG = totalColumn('nFeridosGraves');

					document.getElementById('statisticsTable').innerHTML += "<tr class='active'><th class='aling-middle'>Total</th><td><b>" + nAcidentes + "</b></td><td><b>" + nMortos + "</b></td><td><b>" + nFG + "</b></td></tr>";
				</script>
			</div>
		</div>
	</div>
</body>

</html>