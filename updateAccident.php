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

$qry = "select mortos, feridosgraves, quilometro, latitude, longitude from sinistros where id_sinistro = '$id_sinistro';";
$result = pg_query($con,$qry);
$row = pg_fetch_row($result);

// Valores atuais
$nMortosNow = $row[0];
$nFeridosNow = $row[1];
$kmNow = $row[2];
$latNow = $row[3];
$lonNow =$row[4];

// Comparação de valores
if ($nMortos != $nMortosNow){
    // Foi feito um update aos mortos e feridos
    $updateMFG = TRUE;
}

if ($km != $kmNow) {
    // Foi feito um update aos km
    $updateKm = TRUE;
}

if ($latNow != $lat) {
    // Foi feito um update à latitude
    $updatelat = TRUE;
}

if ($lonNow != $lon){
    // Foi feito um update à longitute
    $updatelon = TRUE;
}

if (pg_num_rows($result) != 0) {
    //Begin transaction
    pg_query("BEGIN") or die("Could not start transaction\n");
    pg_query("LOCK TABLE sinistros IN SHARE ROW EXCLUSIVE MODE;");
    
    // Update mortos e fg
    if ($updateMFG) $sqlUpdateRecord = "UPDATE sinistros SET mortos = '$nMortos', feridosgraves = '$nFeridos' WHERE id_sinistro = $id_sinistro;";

    // Update quilometros
    if ($updateKm) $sqlUpdateRecord = "UPDATE sinistros SET quilometro = '$km' WHERE id_sinistro = $id_sinistro;";
    
    // Update latitude
    if ($updatelat) $sqlUpdateRecord = "UPDATE sinistros SET latitude = '$lat' WHERE id_sinistro = $id_sinistro;";
    
    // Update longitude
    if ($updatelon) $sqlUpdateRecord = "UPDATE sinistros SET longitude = '$lon' WHERE id_sinistro = $id_sinistro;";
    
    $q = pg_query($con,$sqlUpdateRecord);
    pg_query("select pg_sleep(6);");

    if ($q) {
        pg_query("COMMIT") or die("Transaction commit failed\n");	
        $_SESSION['update'] = "success";
        echo 'Success';
        if ($_SESSION['role'] == "admin") { // ADMIN
            header('Location: sinistros.php');
        } else { // USER-CRU
            header('Location: sinistros-cru.php');
        }
        pg_close($con);
    } else {
        pg_query("ROLLBACK") or die("Transaction rollback failed\n"); 
        $_SESSION['update'] = "failed";
        echo 'Failed';
        if ($_SESSION['role'] == "admin") { // ADMIN
            header('Location: sinistros.php');
        } else { // USER-CRU
            header('Location: sinistros-cru.php');
        }
        pg_close($con);
    }
} else {
    echo 'Não existe';
}

?>