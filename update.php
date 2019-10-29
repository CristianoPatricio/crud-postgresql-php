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

if(isset($_GET["user"]))
{
    $userDefault = $_GET["user"];
}

$username = $_POST["username"];
$password = $_POST["password"];
$role = $_POST["role"];

if ($password != null) {
    $password = password_hash($password, PASSWORD_BCRYPT); 
} else {
    $pass = 0;
}

$qry = "select id from utilizador where username LIKE '$userDefault';";
$result = pg_query($con,$qry);
$row = pg_fetch_row($result);
$id_user = $row[0];
echo pg_num_rows($result);


if (pg_num_rows($result) != 0) {
    $sqlUpdateRecord = "";
    if ($pass != 0 ) {
        $sqlUpdateRecord = "UPDATE utilizador SET username = '$username', password = '$password', role = '$role' WHERE id = $id_user;";
    } else {
        $sqlUpdateRecord = "UPDATE utilizador SET username = '$username', role = '$role' WHERE id = $id_user;";
    }

    $q = pg_query($con,$sqlUpdateRecord);
    
    if ($q) {
        $_SESSION['update'] = "success";
        //echo 'Success';
        header('Location: profile.php');
        pg_close($con);
    } else {
        $_SESSION['update'] = "failed";
        //echo 'Failed';
        header('Location: profile.php');
        pg_close($con);
    }
}


?>