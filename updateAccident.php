<?php
        
session_start();

$host = "52.47.199.255";
$user = "ubuntu";
$pass = "1234";
$db = "teste";

// Open a PostgreSQL connection
$con = pg_connect("host=$host dbname=$db user=$user password=$pass") 
or die ("Could not connect to server\n");

$_SESSION['update'] = "";
$pass = 1;

if(isset($_GET["id"]))
{
    $id_sinistro = $_GET["id"];
}


    // GET VARIABLES DATA
    $id_distrito = $_POST['distrito'];
    $id_concelho = $_POST['concelho']; 
    $datahora = $_POST['datahora'];
    $nMortos = $_POST['nMortos'];
    $nFeridos = $_POST['nFGraves'];
    $km = $_POST['quilometro'];
    $via = $_POST['via'];
    $natureza = $_POST['natureza'];
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];

$qry = "select * from sinistros where id_sinistro = '$id_sinistro';";
$result = pg_query($con,$qry);
$row = pg_fetch_row($result);
//echo pg_num_rows($result);

if (pg_num_rows($result) != 0) {
    $sqlUpdateRecord = "BEGIN; SET TRANSACTION ISOLATION LEVEL SERIALIZABLE; UPDATE sinistros SET id_distrito = '$id_distrito', id_concelho = '$id_concelho', datahora = '$datahora', mortos = '$nMortos', feridosgraves = '$nFeridos', via = '$via', quilometro = '$km', natureza = '$natureza', latitude = '$lat', longitude = '$lon' WHERE id_sinistro = $id_sinistro; COMMIT;";
    $q = pg_query($con,$sqlUpdateRecord);
    
    if ($q) {
        $_SESSION['update'] = "success";
        echo 'Success';
        header('Location: sinistros.php');
        pg_close($con);
    } else {
        $_SESSION['update'] = "failed";
        echo 'Failed';
        header('Location: sinistros.php');
        pg_close($con);
    }
} else {
    echo 'Não existe';
}

?>