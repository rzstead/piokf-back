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

    $updatePageQuery = "update pages set title = '"
                        .$data->title."', parent_page_id = '"
                        .$data->parent_page_id."' where id = '".$data->id."'";
    
    $pageUpdateResult = $mysqli->query($updatePageQuery);
    
    /*
        EXAMPLE JSON EXPECTED:
        {
            "id":"2",
            "title":"Updated Page Title",
            "parent_page_id":null
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