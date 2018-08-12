<?php

header("Access-Control-Allow-Origin: http://neumontcsc270.dynu.net:3000");
header("Access-Control-Allow-Headers: Authorization, X-Requested-With");
include "dbconfig.php";

// session_start();
$username = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];
$loginQuery = "select * from users where username = '".$username."'";
$loginResult = $mysqli->query($loginQuery);

$row = mysqli_fetch_array($loginResult);
$isLoggedIn = $row['password'] == $password;

if($isLoggedIn){
    $elementId = $mysqli->real_escape_string($_GET['id']);

    $addAttributeQuery = "insert into element_attributes (element_id, attribute_name, attribute_value) values ('"
                        .$elementId."', '"
                        .$data->attributes[$i]->name."', '"
                        .$data->attributes[$i]->value."')";
    $mysqli->query($addAttributeQuery);
    
    /*
    
    EXAMPLE JSON EXPECTED:
    {
        "name":"href",
        "value":"test.html"
    }
    
    */
} else {
    $errorData = new stdClass();
    $errorData->message = "invalid login credentials";
    echo json_encode($errorData);
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>