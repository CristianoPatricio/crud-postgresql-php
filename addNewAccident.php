<?php
        
session_start();

$host = "52.47.199.255";
$user = "ubuntu";
$pass = "1234";
$db = "teste";

// Open a PostgreSQL connection
$con = pg_connect("host=$host dbname=$db user=$user password=$pass") 
or die ("Could not connect to server\n");

$_SESSION['duplicate'] = "";
$_SESSION['added'] = "";
$_SESSION['failed'] = "";

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

        //Begin transaction
        pg_query("BEGIN") or die("Could not start transaction\n");
        pg_query("LOCK TABLE sinistros IN SHARE MODE;");
//CHECK IF YOUR EXISTS  
        $qry = "select * from sinistros where id_distrito = '$id_distrito' and id_concelho = '$id_concelho' and datahora like '$datahora' and mortos = '$nMortos' and feridosgraves = '$nFeridos' and quilometro = '$km' and via = '$via' and natureza = '$natureza' and latitude = '$lat' and longitude = '$lon';";
        $result = pg_query($con,$qry);
        echo pg_num_rows($result);
        if(pg_num_rows($result) > 0) {
            $_SESSION['duplicate'] = "duplicate";
            if ($_SESSION['role'] == "admin") { // ADMIN
                header('Location: sinistros.php');
            } else { // USER-CRU
                header('Location: sinistros-cru.php');
            }
            pg_close($con);
        } else {
    
            $sql2 = "INSERT INTO sinistros (id_distrito, id_concelho, datahora, mortos, feridosgraves, via, quilometro, natureza, latitude, longitude) VALUES('$id_distrito','$id_concelho','$datahora', '$nMortos', '$nFeridos', '$via', '$km', '$natureza', '$lat', '$lon');";
            $q1 = pg_query($con,$sql2);
    
            if ($q1) {
                pg_query("COMMIT") or die("Transaction commit failed\n");	
                $_SESSION['added'] = "added";
                if ($_SESSION['role'] == "admin") { // ADMIN
                    header('Location: sinistros.php');
                } else { // USER-CRU
                    header('Location: sinistros-cru.php');
                }
                pg_close($con);		
            } else {
                pg_query("ROLLBACK") or die("Transaction rollback failed\n");        
                $_SESSION['failed'] = "failed"; 
                if ($_SESSION['role'] == "admin") { // ADMIN
                    header('Location: sinistros.php');
                } else { // USER-CRU
                    header('Location: sinistros-cru.php');
                }
                pg_close($con);
            }
        }
?>