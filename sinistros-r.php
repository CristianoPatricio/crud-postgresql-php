<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedinUser-R'])) {
	header('Location: login.php');
	exit();
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Sinistros</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">

	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->

	<!--DateTime Picker-->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker-standalone.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>

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
			<?php elseif ($_SESSION['role'] == "user_cru") :?> <!-- USER-CRU-->
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
		<span id="errorMessage"></span>
		<h2>Sinistros</h2>
		<div>
			<?php
			/* Constantes de configuração */
			define('QTDE_REGISTROS', 10);
			define('RANGE_PAGINAS', 5);

			/* Recebe o número da página via parâmetro na URL */
			$pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;

			/* Calcula a linha inicial da consulta */
			$linha_inicial = ($pagina_atual - 1) * QTDE_REGISTROS;

			/* Cria uma conexão PDO com PostgreSQL */
			$pdo = new PDO("pgsql:host=52.47.199.255; dbname=teste;", "ubuntu", "1234");

			/* Instrução de consulta para paginação com PostgreSQL */
			if ((!(isset($_GET['dataInicio'])) || !(isset($_GET['dataFim']))) || $_GET['dataInicio'] == null || $_GET['dataFim'] == null) {
				$sql = "SELECT id_sinistro, id_distrito, id_concelho, datahora, mortos, feridosgraves, via, quilometro, natureza, latitude, longitude FROM sinistros ORDER BY id_sinistro DESC LIMIT " . QTDE_REGISTROS . " OFFSET {$linha_inicial}";
			} else {
				$dataInicio = $_GET['dataInicio'];
				$dataFim = $_GET['dataFim'];

				if (strpos($dataInicio, '-') == 2) { //format: 11-2017
					$dataInicioRevert = substr($dataInicio, 3, 4) . '-' . substr($dataInicio, 0, 2);
				} else { //format: 2017-11
					$dataInicioRevert = substr($dataInicio, 5, 2) . '-' . substr($dataInicio, 0, 4);
				}

				if (strpos($dataFim, '-') == 2) { //format: 11-2017
					$dataFimRevert = substr($dataFim, 3, 4) . substr($dataFim, 0, 2);
				} else { //format: 2017-11
					$dataFimRevert = substr($dataFim, 5, 2) . substr($dataFim, 0, 4);
				}

				$con = pg_connect("host=52.47.199.255 dbname=teste user=ubuntu password=1234");
				//echo $dataInicio; echo " | "; echo "$dataInicioRevert"; echo " | "; echo $dataFim; echo " | "; echo $dataFimRevert;

				$qry1 = "SELECT id_sinistro FROM sinistros WHERE datahora like '%$dataInicio%' or datahora like '%$dataInicioRevert%' ORDER BY id_sinistro DESC LIMIT 1;";
				$result = pg_query($con, $qry1);
				$row = pg_fetch_row($result);
				$id_sinistro_inicial = $row[0];
				//echo $id_sinistro_inicial;
				//echo " \ ";

				$qry2 = "SELECT id_sinistro FROM sinistros WHERE datahora like '%$dataFim%' or datahora like '%$dataFimRevert%' ORDER BY id_sinistro DESC LIMIT 1;";
				$result1 = pg_query($con, $qry2);
				$row1 = pg_fetch_row($result1);
				$id_sinistro_final = $row1[0];
				//echo $id_sinistro_final;

				if ($id_sinistro_inicial > $id_sinistro_final) {
					$id_final = $id_sinistro_final;
					$id_sinistro_final = $id_sinistro_inicial;
					$id_sinistro_inicial = $id_final;
				}

				$sql = "SELECT id_sinistro, id_distrito, id_concelho, datahora, mortos, feridosgraves, via, quilometro, natureza, latitude, longitude FROM sinistros WHERE id_sinistro >= $id_sinistro_inicial AND id_sinistro <= $id_sinistro_final ORDER BY id_sinistro DESC LIMIT " . QTDE_REGISTROS . " OFFSET {$linha_inicial}";
			}

			//echo $sql;
			$stm = $pdo->prepare($sql);
			$stm->execute();
			$dados = $stm->fetchAll(PDO::FETCH_OBJ);

			/* Conta quantos registos existem na tabela */
			if ((!(isset($_GET['dataInicio'])) || !(isset($_GET['dataFim']))) || $_GET['dataInicio'] == null || $_GET['dataFim'] == null) {
				$sqlContador = "SELECT COUNT(*) AS total_registros FROM sinistros";
			} else {
				$sqlContador = "SELECT COUNT(*) AS total_registros FROM sinistros WHERE id_sinistro >= $id_sinistro_inicial AND id_sinistro <= $id_sinistro_final";
			}

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
			<h5 style="color:#4a536e;">Filtrar resultados...</h5>
			<form action="sinistros-r.php" method="get" style="background-color: #e3e8e5; padding: 10px; border-radius: 5px;">
				<div class="form-row">
					<div class='form-group col-md-2'>
						<i class="fas fa-search grey-text"></i>
						<label for="role"> Data de Início </label>
						<div class='input-group date' id='datetimepickerSearchInitial'>
							<input name="dataInicio" type='text' class="form-control" value="" id="input-picker-initialDate" />
						</div>
					</div>
					<script type="text/javascript">
						var d = new Date();

						$("#input-picker-initialDate").datetimepicker({
							format: 'MM-YYYY',
							defaultDate: d
						});

						$(function() {
							$('#input-picker-initialDate').datetimepicker();
						});
					</script>
					<div class='form-group col-md-3'>
						<i class="fas fa-search grey-text"></i>
						<label for="role"> Data de Fim </label>
						<div class='input-group date' id='datetimepickerSearchEnd'>
							<input name="dataFim" type='text' class="form-control" value="" id="input-picker-finalDate" />
							<button type="submit" class="btn btn-primary"><i class="fas fa-search-plus"></i> Pesquisar</button>
						</div>
					</div>
					<script type="text/javascript">
						var d = new Date();

						$("#input-picker-finalDate").datetimepicker({
							format: 'MM-YYYY',
							defaultDate: d
						});

						$(function() {
							$('#input-picker-finalDate').datetimepicker();
						});
					</script>
				</div>
			</form>
			<hr>
			<div class='container'>
				<div class="row">
					<?php if (!empty($dados)) : ?>
						<table class="table table-striped table-bordered">
							<thead>
								<tr class='active'>
									<th class="align-middle">Cód. Sinistro</th>
									<th class="align-middle">Data/hora</th>
									<th class="align-middle">Natureza</th>
									<th class="align-middle">Ações</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($dados as $sinistro) : ?>
									<tr>
										<td><?= $sinistro->id_sinistro ?></td>
										<td><?= $sinistro->datahora ?></td>
										<td><?= $sinistro->natureza ?></td>
										<td><a href="#modalViewDetails" class="modalVerDetalhes btn btn-info" data-toggle="modal" data-backdrop='static' data-keyboard='false' data-id="<?= $sinistro->id_sinistro ?>"><i class="fas fa-search-plus"></i></a> 
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>

						<div class='box-paginacao'>
							<?php if ((!(isset($_GET['dataInicio'])) || !(isset($_GET['dataFim']))) || $_GET['dataInicio'] == null || $_GET['dataFim'] == null) : ?>
								<a class='btn btn-info box-navegacao <?= $exibir_botao_inicio ?>' href="sinistros-r.php?page=<?= $primeira_pagina ?>" title="Primeira Página">
									<<</a> <a class='btn btn-info box-navegacao <?= $exibir_botao_inicio ?>' href="sinistros-r.php?page=<?= $pagina_anterior ?>" title="Página Anterior">
										<</a> <?php
														/* Loop para montar a páginação central com os números */
														for ($i = $range_inicial; $i <= $range_final; $i++) :
															$destaque = ($i == $pagina_atual) ? 'destaque' : '';
															?> <a class='box-numero <?= $destaque ?>' href="sinistros-r.php?page=<?= $i ?>"><?= $i ?>
								</a>
							<?php endfor; ?>
							<a class='btn btn-info box-navegacao <?= $exibir_botao_final ?>' href="sinistros-r.php?page=<?= $proxima_pagina ?>" title="Próxima Página">></a>
							<a class='btn btn-info box-navegacao <?= $exibir_botao_final ?>' href="sinistros-r.php?page=<?= $ultima_pagina ?>" title="Última Página">>></a>
						<?php else : ?>
							<a class='btn btn-info box-navegacao <?= $exibir_botao_inicio ?>' href="sinistros-r.php?page=<?= $primeira_pagina ?>&dataInicio=<?php echo $_GET['dataInicio']; ?>&dataFim=<?php echo $_GET['dataFim']; ?>" title="Primeira Página">
								<<</a> <a class='btn btn-info box-navegacao <?= $exibir_botao_inicio ?>' href="sinistros-r.php?page=<?= $pagina_anterior ?>&dataInicio=<?php echo $_GET['dataInicio']; ?>&dataFim=<?php echo $_GET['dataFim']; ?>" title="Página Anterior">
									<</a> <?php
													/* Loop para montar a páginação central com os números */
													for ($i = $range_inicial; $i <= $range_final; $i++) :
														$destaque = ($i == $pagina_atual) ? 'destaque' : '';
														?> <a class='box-numero <?= $destaque ?>' href="sinistros-r.php?page=<?= $i ?>&dataInicio=<?php echo $_GET['dataInicio']; ?>&dataFim=<?php echo $_GET['dataFim']; ?>"><?= $i ?>
							</a>
						<?php endfor; ?>
						<a class='btn btn-info box-navegacao <?= $exibir_botao_final ?>' href="sinistros-r.php?page=<?= $proxima_pagina ?>&dataInicio=<?php echo $_GET['dataInicio']; ?>&dataFim=<?php echo $_GET['dataFim']; ?>" title="Próxima Página">></a>
						<a class='btn btn-info box-navegacao <?= $exibir_botao_final ?>' href="sinistros-r.php?page=<?= $ultima_pagina ?>&dataInicio=<?php echo $_GET['dataInicio']; ?>&dataFim=<?php echo $_GET['dataFim']; ?>" title="Última Página">>></a>
					<?php endif; ?>
						</div>
					<?php else : ?>
						<p class="bg-danger">Nenhum registo encontrado!</p>
					<?php endif; ?>


				</div>
			</div>
		</div>
	</div>
	<script>
		$('.modalVerDetalhes').click(function() {
			var id = $(this).attr('data-id');
			$.ajax({
				url: "viewDetails.php?id=" + id,
				cache: false,
				success: function(result) {
					$(".modal-content").html(result);
				}
			});
		});
	</script>

	<!-- MODAL DETALHES -->
	<div class="modal fade modal" id="modalViewDetails" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

			</div>
		</div>
	</div>
</body>

</html>