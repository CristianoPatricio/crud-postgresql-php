<?php

session_start();

$host = "52.47.199.255";
$user = "ubuntu";
$pass = "1234";
$db = "teste";

// Open a PostgreSQL connection
$con = pg_connect("host=$host dbname=$db user=$user password=$pass") 
or die ("Could not connect to server\n");

$_SESSION['loggedinAdmin'] = "";

if($con) {
       echo 'connected\n';
    } else {
        echo 'there has been an error connecting\n';
    }

	$username = $_POST['username'];
	$password = $_POST['password'];
   
    $row = pg_fetch_row($result);
    $id = intval($row[0]);
	$pg_qry = "select password from utilizador where username like '$username'";
	$result1 = pg_query($con,$pg_qry);
	$row = pg_fetch_row($result1);

    $savedpass = $row[0];
    $validPassword = password_verify($password, $savedpass);
    if($validPassword){
        $_SESSION['loggedinAdmin'] = "success";
        $_SESSION['username'] = $username; 
        header("Location: home.php");     
		pg_close($con);
	}
	else {
		$_SESSION['loggedinAdmin'] = "failed"; 
        header("Location: index.php");
		pg_close($con);
	
}

?>