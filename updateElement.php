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

    $addElementQuery = "update elements set type = '"
                        .$data->type."', sequence = '"
                        .$data->sequence."', inner_html = '"
                        .$data->inner_html."', page_id = '"
                        .$data->page_id."' where id = '"
                        .$data->id."'";
    
    $elementAddResult = $mysqli->query($addElementQuery);
    
    /*
        EXAMPLE JSON EXPECTED:
        {
            "id":"5",
            "type":"a",
            "sequence":"3",
            "inner_html":"Test Link",
            "page_id":"4"
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