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

    $addPageQuery = "insert into pages (title, parent_page_id) values ('"
                        .$data->title."', '"
                        .$data->parent_page_id."')";

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
    $errorData = new stdClass();
    $errorData->message = "invalid login credentials";
    echo json_encode($errorData);
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>
