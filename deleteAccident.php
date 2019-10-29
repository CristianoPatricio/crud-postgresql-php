<?php
        
session_start();

$host = "52.47.199.255";
$user = "ubuntu";
$pass = "1234";
$db = "teste";

// Open a PostgreSQL connection
$con = pg_connect("host=$host dbname=$db user=$user password=$pass") 
or die ("Could not connect to server\n");

$_SESSION['delete'] = "";

if(isset($_GET["id_sinistro"]))
{
    $id_sinistro = $_GET["id_sinistro"];
}

$qry = "select * from sinistros where id_sinistro = '$id_sinistro';";
$result = pg_query($con,$qry);
$row = pg_fetch_row($result);

echo pg_num_rows($result);

if (pg_num_rows($result) != 0) {
    $sqlDeleteRecord = "BEGIN; DO $$ BEGIN PERFORM pg_sleep(5); END $$; DELETE FROM sinistros WHERE id_sinistro = $id_sinistro; COMMIT; END;";
    $q = pg_query($con,$sqlDeleteRecord);
    
    if ($q) {
        $_SESSION['delete'] = "success";
        header('Location: sinistros.php');
        pg_close($con);
    } else {
        $_SESSION['delete'] = "failed";
        header('Location: sinistros.php');
        pg_close($con);
    }
}



?>