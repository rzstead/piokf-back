<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"]){
    $data = json_decode(file_get_contents('php://input'));

    $updateThemeQuery = "update config set current_theme = '"
                        .$data->current_theme."' where 1 = 1'";
    
    $themeUpdateResult = $mysqli->query($updateThemeQuery);
    
    /*
        EXAMPLE JSON EXPECTED:
        {
            "current_theme":"dark.css"
        }
    */
} else {
    echo "invalid login credentials";
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>