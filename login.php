<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: true");
include "dbconfig.php";

session_start();

$username = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

$loginQuery = "select * from users where username = '".$username."'";

$loginResult = $mysqli->query($loginQuery);

$row = mysqli_fetch_array($loginResult);
$_SESSION["isLoggedIn"] = $row['password'] == $password;

if($_SESSION["isLoggedIn"]){
    $messageData = new stdClass();
    $messageData->message = "login successful";
    echo json_encode($messageData);
} else {
    $errorData = new stdClass();
    $errorData->message = "invalid login credentials";
    echo json_encode($errorData);
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>