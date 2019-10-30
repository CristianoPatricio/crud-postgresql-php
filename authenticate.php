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
$_SESSION['loggedinUser-CRU'] = "";
$_SESSION['loggedinUser-R'] = "";

if($con) {
       echo 'connected\n';
    } else {
        echo 'there has been an error connecting\n';
    }

	$username = $_POST['username'];
	$password = $_POST['password'];
   
   // Verify password
	$pg_qry = "select password from utilizador where username like '$username'";
	$result1 = pg_query($con,$pg_qry);
    $row = pg_fetch_row($result1);
    
    $savedpass = $row[0];
    $validPassword = password_verify($password, $savedpass);

     //Verify user role
     $pg_qry = "select role from utilizador where username like '$username'";
     $result2 = pg_query($con,$pg_qry);
     $row = pg_fetch_row($result2);
     $role = $row[0];

    if($validPassword){
        if ($role == "admin") { //Loggedin ADMIN
            $_SESSION['loggedinAdmin'] = "success"; 
        } else if ($role == "user_cru") { //Loggedin USER-CRU
            $_SESSION['loggedinUser-CRU'] = "success";
        } else {  //Loggedin USER-R
            $_SESSION['loggedinUser-R'] = "success";
        } 

        $_SESSION['username'] = $username; 
        $_SESSION['role'] = $role;
        header("Location: home.php"); 
		pg_close($con);
	}
	else {
        $_SESSION['loggedinAdmin'] = "failed"; 
        $_SESSION['loggedinUser-CRU'] = "failed";
        $_SESSION['loggedinUser-R'] = "failed";
        header("Location: login.php");
		pg_close($con);
	
    }

?>