<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"] || true){
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