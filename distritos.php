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
	<title>Distritos</title>
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
		<h2>Lista de Distritos</h2>
		<div>
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
			$sql = "SELECT id_distrito,nome_distrito FROM distritos LIMIT " . QTDE_REGISTROS . " OFFSET {$linha_inicial}";
			$stm = $pdo->prepare($sql);
			$stm->execute();
			$dados = $stm->fetchAll(PDO::FETCH_OBJ);

			/* Conta quantos registos existem na tabela */
			$sqlContador = "SELECT COUNT(*) AS total_registros FROM distritos";
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
                                    <th class="aling-middle col-md-2">Cód. Distrito</th>
                                    <th class="align-middle">Nome do Distrito</th>							
								</tr>
							</thead>
							<tbody>
								<?php foreach ($dados as $distrito) : ?>
									<tr>
                                        <td class="col-md-2"><?= $distrito->id_distrito ?></td>
										<td><?= $distrito->nome_distrito ?></td>							
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>

						<div class='box-paginacao'>
							<a class='btn btn-info box-navegacao <?= $exibir_botao_inicio ?>' href="distritos.php?page=<?= $primeira_pagina ?>" title="Primeira Página">
								<<</a> <a class='btn btn-info box-navegacao <?= $exibir_botao_inicio ?>' href="distritos.php?page=<?= $pagina_anterior ?>" title="Página Anterior">
									<</a> <?php
												/* Loop para montar a páginação central com os números */
												for ($i = $range_inicial; $i <= $range_final; $i++) :
													$destaque = ($i == $pagina_atual) ? 'destaque' : '';
													?> <a class='box-numero <?= $destaque ?>' href="distritos.php?page=<?= $i ?>"><?= $i ?>
							</a>
						<?php endfor; ?>

						<a class='btn btn-info box-navegacao <?= $exibir_botao_final ?>' href="distritos.php?page=<?= $proxima_pagina ?>" title="Próxima Página">></a>
						<a class='btn btn-info box-navegacao <?= $exibir_botao_final ?>' href="distritos.php?page=<?= $ultima_pagina ?>" title="Última Página">>></a>
						</div>
					<?php else : ?>
                        <p class="bg-danger">Nenhum registo encontrado!</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</body>

</html>