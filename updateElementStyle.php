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

    $updateStyleQuery = "update element_styles set value = '"
                        .$data->style_value."' where element_id = '"
                        .$data->element_id."' and style_attribute = '"
                        .$data->style_attribute."'";
    
    $styleUpdateResult = $mysqli->query($updateStyleQuery);
    
    /*
        EXAMPLE JSON EXPECTED:
        {
            "element_id":"5",
            "style_attribute":"display",
            "style_value":"block"
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