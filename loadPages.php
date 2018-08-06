<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

$pages = array();

$parentPageQuery = "select * from PAGES where parent_page_id is null";
$parentPagesResult = $mysqli->query($parentPageQuery);

while($row = mysqli_fetch_array($parentPagesResult))
{
    $currentRoot = new stdClass();
    $currentRoot->id = $row['id'];
    $currentRoot->children = array();
    $currentRoot->title = $row['title'];
    $currentRootQuery = "select * from PAGES where parent_page_id = ".$row['id'];
    $currentRootResult = $mysqli->query($currentRootQuery);
    while($childRow = mysqli_fetch_array($currentRootResult))
    {
        $child = new stdClass();
        $child->title = $childRow['title'];
        $child->id = $childRow['id'];
        array_push($currentRoot->children, $child);
    }
    array_push($pages, $currentRoot);
}

echo json_encode($pages);

?>