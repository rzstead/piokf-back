<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"]){
    $data = json_decode(file_get_contents('php://input'));

    $addPageQuery = "insert into pages (title, parent_page_id) values ('"
                        .$data->title."', '"
                        .$data->parent_page_id."')";
    
    $pageAddResult = $mysqli->query($addPageQuery);
    
    /*
        EXAMPLE JSON EXPECTED:
        {
            "title":"newPageTitle",
            "parent_page_id":"3" //SET TO NULL IF PARENT
        }
    */
} else {
    echo "invalid login credentials";
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>