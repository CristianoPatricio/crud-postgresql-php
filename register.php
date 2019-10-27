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

        $username = $_POST['username']; //"Teste1";
        $password = $_POST['password']; //"Test1";
        $password = password_hash($password, PASSWORD_BCRYPT);
        $level = $_POST['role']; //'admin';

//CHECK IF YOUR EXISTS  
        $qry = "select * from utilizador where username LIKE '$username';";
        $result = pg_query($con,$qry);
        echo pg_num_rows($result);
        if(pg_num_rows($result) > 0) {
            $_SESSION['duplicate'] = "duplicate";
            header('Location: profile.php');
            pg_close($con);
        }
        else {
    
            $sql2 = "INSERT INTO utilizador (username,password,role) VALUES('$username','$password','$level');";
            $q1 = pg_query($con,$sql2);
    
            if ($q1) {	
                $_SESSION['added'] = "added";
                header('Location: profile.php');
                pg_close($con);		
            } else {        
                $_SESSION['failed'] = "failed"; 
                header('Location: profile.php');
                pg_close($con);
            }
        }
?>