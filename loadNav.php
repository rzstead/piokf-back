<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

$pages = array();

$parentPageQuery = "select * from pages where parent_page_id is null";
$parentPagesResult = $mysqli->query($parentPageQuery);

while($row = mysql_fetch_array($parentPagesResult))
{
    $currentRoot = new stdClass();
    $currentRoot->children = array();
    $currentRoot->title = $row['title'];
    $currentRootQuery = "select * from pages where parent_page_id = ".$row['id'];
    $currentRootResult = $mysqli->query($currentRootQuery);
    while($childRow = mysql_fetch_array($currentRootResult))
    {
        $child = new stdClass();
        $child->title = $childRow['title'];
        array_push($currentRoot->children, $currentRoot);
    }
    array_push($pages, $currentRoot);
}

// function addChildPages($result, $parentObject)
// {
//     while($row = mysql_fetch_array($result))
//     {
//        $currentRoot = new stdClass();
//        $currentRoot->children = array();
//        $currentRoot->title = $row['title'];
//        $currentRootQuery = "select * from pages where parent_page_id = ".$row['id'];
//        $currentRootResult = $mysqli->query($currentRootQuery);
//        array_push($parentObject->children, $currentRoot);
//        addChildPages($currentRootResult, $currentRoot);
//     }
// }

 echo json_encode($pages);

?>