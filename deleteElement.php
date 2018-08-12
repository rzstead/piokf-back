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
    $element_id = $mysqli->real_escape_string($_GET['id']);

    //Delete all element attributes/styles of all elements that exist on the page
    $elementAttributesQuery = "delete from element_attributes where element_id = ".$element_id;
    $elementAttributesResult = $mysqli->query($elementAttributesQuery);

    $elementStylesQuery = "delete from element_styles where element_id = ".$element_id;
    $elementStylesResult = $mysqli->query($elementStylesQuery);

    //Delete the element
    $elementsQuery = "delete from elements where id = ".$element_id;
    $elementsResult = $mysqli->query($elementsQuery);
} else {
    $errorData = new stdClass();
    $errorData->message = "invalid login credentials";
    echo json_encode($errorData);
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>