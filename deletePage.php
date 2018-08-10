<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"] || true){
    $page_id = $mysqli->real_escape_string($_GET['id']);
    $childPageSelectionQuery = "select * from pages where parent_page_id = ".$page_id;
    $childPageSelectionResult = $mysqli->query($childPageSelectionQuery);
    $childPageIds = array();

    while($row = mysqli_fetch_array($childPageSelectionResult)){
        array_push($childPageIds, $row['id']);
    }

    for($i = 0; $i < count($childPageIds); $i++){
        deletePage($childPageIds[$i]);
    }
    deletePage($page_id);
} else {
    $errorData = new stdClass();
    $errorData->message = "invalid login credentials";
    echo json_encode($errorData);
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

function deletePage($deletedPageId) {
    include "dbconfig.php";
    //Delete all element attributes/styles of all elements that exist on the page
    $elementSelectionQuery = "select * from elements where page_id = ".$deletedPageId;
    $elementSelectionResult = $mysqli->query($elementSelectionQuery);
    $elementIds = array();

    while($row = mysqli_fetch_array($elementSelectionResult)){
        array_push($elementIds, $row['id']);
    }

    for($i = 0; $i < count($elementIds); $i++){
        $elementAttributesQuery = "delete from element_attributes where element_id = ".$elementIds[$i];
        $elementAttributesResult = $mysqli->query($elementAttributesQuery);

        $elementStylesQuery = "delete from element_styles where element_id = ".$elementIds[$i];
        $elementStylesResult = $mysqli->query($elementStylesQuery);

        //Delete the element
        $elementsQuery = "delete from elements where id = ".$elementIds[$i];
        $elementsResult = $mysqli->query($elementsQuery);
    }
    //Delete the page
    $pageQuery = "delete from pages where id = ".$deletedPageId;
    $pageResult = $mysqli->query($pageQuery);
}

?>