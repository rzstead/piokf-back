<?php
header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"]){
} else {
}

$data = json_decode(file_get_contents('php://input'));
echo "$data"
?>
