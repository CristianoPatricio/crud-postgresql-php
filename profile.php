<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedinAdmin'])) {
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
			<a href="sinistros.php"><i class="fas fa-car-crash"></i>Sinistros</a>
			<a href="profile.php"><i class="fas fa-user-circle"></i><?= $_SESSION['username'] ?></a>
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
		<div id="adminConfigOptions">
			<h2>Registar novo utilizador</h2> <br>
			<form action="register.php" method="post">
				<div class="form-row">
					<div class="form-group col-md-4">
						<i class="fas fa-user prefix grey-text"></i>
						<label for="username">Username</label>
						<input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
					</div>
					<div class="form-group col-md-4">
						<i class="fas fa-lock prefix grey-text"></i>
						<label for="password">Password</label>
						<input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
					</div>
					<div class="form-group col-md-4">
						<i class="fas fa-user-shield grey-text"></i>
						<label for="role">Permissões</label>
						<select class="form-control" name="role">
							<option value="admin">Admin</option>
							<option value="user_cru">User-CRU</option>
							<option value="user_R">User-R</option>
						</select>
					</div>
				</div>
				<span id="errorMessage"></span>
				<button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Registar</button>
			</form>
			<br>
			<h2>Gerir utilizadores do sistema</h2> <br>
			<?php
			/* Constantes de configuração */
			define('QTDE_REGISTROS', 10);
			define('RANGE_PAGINAS', 1);

			/* Recebe o número da página via parâmetro na URL */
			$pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;

			/* Calcula a linha inicial da consulta */
			$linha_inicial = ($pagina_atual - 1) * QTDE_REGISTROS;

			/* Cria uma conexão PDO com PostgreSQL */
			$pdo = new PDO("pgsql:host=52.47.199.255; dbname=teste;", "ubuntu", "1234");

			/* Instrução de consulta para paginação com PostgreSQL */
			$pdo->beginTransaction();

			$sql = "SELECT username, role FROM utilizador LIMIT " . QTDE_REGISTROS . " OFFSET {$linha_inicial}";
			$stm = $pdo->prepare($sql);
			$stm->execute();
			$dados = $stm->fetchAll(PDO::FETCH_OBJ);

			$pdo->commit();

			/* Conta quantos registos existem na tabela */
			$sqlContador = "SELECT COUNT(*) AS total_registros FROM utilizador";
			$stm = $pdo->prepare($sqlContador);
			$stm->execute();
			$valor = $stm->fetch(PDO::FETCH_OBJ);

			/* Idêntifica a primeira página */
			$primeira_pagina = 1;

			/* Cálcula qual será a última página */
			$ultima_pagina  = ceil($valor->total_registros / QTDE_REGISTROS);

			/* Cálcula qual será a página anterior em relação a página atual em exibição */
			$pagina_anterior = ($pagina_atual > 1) ? $pagina_atual - 1 : 0;

			/* Cálcula qual será a pŕoxima página em relação a página atual em exibição */
			$proxima_pagina = ($pagina_atual < $ultima_pagina) ? $pagina_atual + 1 : 0;

			/* Cálcula qual será a página inicial do nosso range */
			$range_inicial  = (($pagina_atual - RANGE_PAGINAS) >= 1) ? $pagina_atual - RANGE_PAGINAS : 1;

			/* Cálcula qual será a página final do nosso range */
			$range_final   = (($pagina_atual + RANGE_PAGINAS) <= $ultima_pagina) ? $pagina_atual + RANGE_PAGINAS : $ultima_pagina;

			/* Verifica se vai exibir o botão "Primeiro" e "Pŕoximo" */
			$exibir_botao_inicio = ($range_inicial < $pagina_atual) ? 'mostrar' : 'esconder';

			/* Verifica se vai exibir o botão "Anterior" e "Último" */
			$exibir_botao_final = ($range_final > $pagina_atual) ? 'mostrar' : 'esconder';

			?>
			<div class='container'>
				<div class="row">
					<?php if (!empty($dados)) : ?>
						<table class="table table-striped table-bordered">
							<thead>
								<tr class='active'>
									<th class="align-middle">Username</th>
									<th class="align-middle">Permissões</th>
									<th class="align-middle">Ações</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($dados as $user) : ?>
									<tr>
										<td><?= $user->username ?></td>
										<td><?= $user->role ?></td>
										<td><a class="modalEliminarUser btn btn-danger" href="#modalDeleteConfirmation" data-toggle="modal" data-backdrop='static' data-keyboard='false' data-user-name="<?= $user->username ?>"><i class="fas fa-trash"></i> Eliminar</a> <a href="#modalRegisterForm" class="modalEditarUser btn btn-warning" data-toggle="modal" data-backdrop='static' data-keyboard='false' data-user-name="<?= $user->username ?>" data-user-role="<?= $user->role ?>"><i class="fas fa-user-edit"></i> Editar</a></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>

						<div class='box-paginacao'>
							<a class='btn btn-info box-navegacao <?= $exibir_botao_inicio ?>' href="profile.php?page=<?= $primeira_pagina ?>" title="Primeira Página">
								<<</a> <a class='btn btn-info box-navegacao <?= $exibir_botao_inicio ?>' href="profile.php?page=<?= $pagina_anterior ?>" title="Página Anterior">
									<</a> <?php
												/* Loop para montar a páginação central com os números */
												for ($i = $range_inicial; $i <= $range_final; $i++) :
													$destaque = ($i == $pagina_atual) ? 'destaque' : '';
													?> <a class='box-numero <?= $destaque ?>' href="profile.php?page=<?= $i ?>"><?= $i ?>
							</a>
						<?php endfor; ?>

						<a class='btn btn-info box-navegacao <?= $exibir_botao_final ?>' href="profile.php?page=<?= $proxima_pagina ?>" title="Próxima Página">></a>
						<a class='btn btn-info box-navegacao <?= $exibir_botao_final ?>' href="profile.php?page=<?= $ultima_pagina ?>" title="Última Página">>></a>
						</div>
					<?php else : ?>
						<p class="bg-danger">Nenhum registo encontrado!</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<script>
		if ("<?php echo $_SESSION['duplicate']; ?>" === "duplicate") {
			document.querySelector("#errorMessage").innerHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert" style="width: 350px; text-align:center; margin:auto;"> O utilizador já existe! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			<?php $_SESSION['duplicate'] = ""; ?>
		} else if ("<?php echo $_SESSION['added']; ?>" === "added") {
			document.querySelector("#errorMessage").innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 350px; text-align:center; margin:auto;"> Utilizador inserido com sucesso! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			<?php $_SESSION['added'] = ""; ?>
		} else if ("<?php echo $_SESSION['failed']; ?>" === "failed") {
			document.querySelector("#errorMessage").innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 350px; text-align:center; margin:auto;"> Ups! Erro ao inserir utilizador... <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			<?php $_SESSION['failed'] = ""; ?>
		}

		if ("<? echo $_SESSION['delete']; ?>" === "success") {
			document.querySelector("#msgActions").innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 350px; text-align:center; margin:auto;"> Utilizador eliminado com sucesso! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			<?php $_SESSION['delete'] = ""; ?>
		}

		if ("<? echo $_SESSION['update']; ?>" === "success") {
			document.querySelector("#msgActions").innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 350px; text-align:center; margin:auto;"> Utilizador atualizado com sucesso! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			<?php $_SESSION['update'] = ""; ?>
		}

		$('.modalEditarUser').click(function() {
			var username = $(this).attr('data-user-name');
			var role = $(this).attr('data-user-role');
			$.ajax({
				url: "editModal.php?username=" + username + "&role=" + role,
				cache: false,
				success: function(result) {
					$(".modal-content").html(result);
				}
			});
		});

		$('.modalEliminarUser').click(function() {
			var username = $(this).attr('data-user-name');
			$.ajax({
				url: "deleteModal.php?username=" + username,
				cache: false,
				success: function(result) {
					$(".modal-content").html(result);
				}
			});
		});
	</script>

	<!-- MODAL EDITAR -->
	<div class="modal fade modal" id="modalRegisterForm" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

			</div>
		</div>
	</div>

	<!-- MODAL ELIMINAR -->
	<div class="modal fade modal" id="modalDeleteConfirmation" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

			</div>
		</div>
	</div>
</body>

</html>