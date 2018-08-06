<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

$elementId = $mysqli->real_escape_string($_GET['id']);

$addStyleQuery = "insert into element_styles (element_id, style_attribute, style_value) values ('"
                    .$elementId."', '"
                    .$data->styles[$i]->attribute."', '"
                    .$data->styles[$i]->value."')";
$mysqli->query($addStyleQuery);

/*

EXAMPLE JSON EXPECTED:
{
    "attribute":"font-size",
    "value":"2em"
}

*/

?>