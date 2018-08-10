<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

$parentPageQuery = "select * from pages where parent_page_id is null order by id desc limit 1";
$parentPagesResult = $mysqli->query($parentPageQuery);

$row = mysqli_fetch_array($parentPagesResult);
$currentRoot = new stdClass();
$currentRoot->id = $row['id'];

echo json_encode($currentRoot);

?>