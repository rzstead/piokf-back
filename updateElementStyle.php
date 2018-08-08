<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"]){
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
    echo "invalid login credentials";
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>