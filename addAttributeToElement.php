<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

$elementId = $mysqli->real_escape_string($_GET['id']);

$addAttributeQuery = "insert into element_attributes (element_id, attribute_name, attribute_value) values ('"
                    .$elementId."', '"
                    .$data->attributes[$i]->name."', '"
                    .$data->attributes[$i]->value."')";
$mysqli->query($addAttributeQuery);

/*

EXAMPLE JSON EXPECTED:
{
    "name":"href",
    "value":"test.html"
}

*/

?>