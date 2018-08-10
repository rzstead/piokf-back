<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"] || true){
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
    $errorData = new stdClass();
    $errorData->message = "invalid login credentials";
    echo json_encode($errorData);
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>