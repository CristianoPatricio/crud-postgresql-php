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

if(isset($_GET["user"]))
{
    $username = $_GET["user"];
}

$qry = "select id from utilizador where username LIKE '$username';";
$result = pg_query($con,$qry);
$row = pg_fetch_row($result);
$id_user = $row[0];
//echo $id_user;

if (pg_num_rows($result) != 0) {
    //Begin transaction
    pg_query("BEGIN") or die("Could not start transaction\n");
    pg_query("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;");

    $sqlDeleteRecord = "DELETE FROM utilizador WHERE id = $id_user;";
    $q = pg_query($con,$sqlDeleteRecord);
    
    if ($q) {
        pg_query("COMMIT") or die("Transaction commit failed\n");
        $_SESSION['delete'] = "success";
        header('Location: profile.php');
        pg_close($con);
    } else {
        pg_query("ROLLBACK") or die("Transaction rollback failed\n"); 
        $_SESSION['delete'] = "failed";
        //echo $_SESSION['delete'];
        header('Location: profile.php');
        pg_close($con);
    }
}



?>