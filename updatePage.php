<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"]){
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
    echo "invalid login credentials";
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>