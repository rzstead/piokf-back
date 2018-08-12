<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

//session_start();

// $data = json_decode(file_get_contents('php://input'));
// $username = $data->username;
// $password = $data->password;
$username = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];
$loginQuery = "select * from users where username = '".$username."'";
$loginResult = $mysqli->query($loginQuery);

$row = mysqli_fetch_array($loginResult);
$isLoggedIn = $row['password'] == $password;

//$_SESSION["isLoggedIn"] = $row['password'] == $password;

if($isLoggedIn){
    $authInfo = "Basic ".base64_encode($username.":".$password);

    $messageData = new stdClass();
    $messageData->message = "login successful";
    $messageData->token = $authInfo;
    echo json_encode($messageData);
} else {
    $errorData = new stdClass();
    $errorData->message = "invalid login credentials";
    echo json_encode($errorData);
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>