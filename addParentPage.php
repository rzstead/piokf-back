<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"] || true){
    $data = json_decode(file_get_contents('php://input'));

    $addPageQuery = "insert into pages (title) values ('"
                        .$data->title."')";
    
    $pageAddResult = $mysqli->query($addPageQuery);

    if($pageAddResult){
        $newPageData = new stdClass();

        $pageQuery = "select * from pages order by id desc limit 1";
        $pageResult = $mysqli->query($pageQuery);
        $row = mysqli_fetch_array($pageResult);
        $newPageData->id = $row['id'];
        $newPageData->title = $row['title'];

        echo json_encode($newPageData);
    }
    
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