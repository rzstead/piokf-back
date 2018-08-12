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
    $data = json_decode(file_get_contents('php://input'));

    $updateAttributeQuery = "update element_attributes set value = '"
                        .$data->attribute_value."' where element_id = '"
                        .$data->element_id."' and attribute_name = '"
                        .$data->attribute_name."'";
    
    $attributeUpdateResult = $mysqli->query($updateAttributeQuery);
    
    /*
        EXAMPLE JSON EXPECTED:
        {
            "element_id":"5",
            "attribute_name":"href",
            "attribute_value":"newLink.html"
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