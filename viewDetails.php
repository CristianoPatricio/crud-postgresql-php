<?php
$id = $_GET['id'];

$host = "52.47.199.255";
$user = "ubuntu";
$pass = "1234";
$db = "teste";

// Open a PostgreSQL connection
$con = pg_connect("host=$host dbname=$db user=$user password=$pass")
    or die("Could not connect to server\n");

$qry = "select * from sinistros where id_sinistro = $id;";
$result = pg_query($con, $qry);
$row = pg_fetch_row($result);

$id_distrito = $row[1];
$id_concelho =  $row[2];

$qryFindDistrito = "select nome_distrito from distritos where id_distrito = $id_distrito;";
$result1 = pg_query($con, $qryFindDistrito);
$distrito = pg_fetch_row($result1)[0];


$qryFindConcelho = "select nome_concelho from concelhos where id_concelho = $id_concelho;";
$result2 = pg_query($con, $qryFindConcelho);
$concelho = pg_fetch_row($result2)[0];

// Data
$id_sinistro = $row[0];
$datahora = $row[3];
$mortos = $row[4];
$feridosgraves = $row[5];
$km = $row[6];
$natureza = $row[7];

?>
<div class="modal-header text-center">
    <h4 class="modal-title w-100 font-weight-bold">Detalhes do sinistro #<?php echo $id_sinistro; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body mx-3">
    <p><b>Código:</b> <?php echo $id_sinistro; ?></p>
    <p><b>Data/hora:</b> <?php echo $datahora; ?></p>
    <p><b>Distrito:</b> <?php echo $distrito; ?></p>
    <p><b>Concelho:</b> <?php echo $concelho; ?></p>
    <p><b>Nº Mortos:</b> <?php echo $mortos; ?></p>
    <p><b>Nº Feridos Graves:</b> <?php echo $feridosgraves; ?></p>
    <p><b>Quilómetro:</b> <?php echo $km; ?></p>
    <p><b>Natureza:</b> <?php echo $natureza; ?></p>
</div>
<div class="modal-footer d-flex justify-content-center">
    <button type="button" data-dismiss="modal" class="btn btn-primary">Fechar</button>
</div>