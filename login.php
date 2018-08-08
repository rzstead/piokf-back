<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

$username = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

$loginQuery = "select * from users where username = '".$username."'";

$loginResult = $mysqli->query($loginQuery);

$row = mysqli_fetch_array($loginResult);
$_SESSION["isLoggedIn"] = $row['password'] == $password;

if($_SESSION["isLoggedIn"]){
    echo "login successful.";
} else {
    echo "invalid login credentials";
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>