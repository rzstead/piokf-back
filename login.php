<?php

header("Access-Control-Allow-Origin: 69.27.21.152");
header("Access-Control-Allow-Credentials: true")
include "dbconfig.php";

session_start();

$data = json_decode(file_get_contents('php://input'));
$username = $data->username;
$password = $data->password;

$loginQuery = "select * from users where username = '".$username."'";

$loginResult = $mysqli->query($loginQuery);

$row = mysqli_fetch_array($loginResult);
$_SESSION["isLoggedIn"] = $row['password'] == $password;

if($_SESSION["isLoggedIn"]){
    $messageData = new stdClass();
    $messageData->message = "login successful";
    $messageData->sessionId = session_id();
    echo json_encode($messageData);
} else {
    $errorData = new stdClass();
    $errorData->message = "invalid login credentials";
    echo json_encode($errorData);
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>