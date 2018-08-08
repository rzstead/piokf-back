<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"]){
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
    echo "invalid login credentials";
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

?>