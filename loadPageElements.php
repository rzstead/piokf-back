<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

$page_id = $mysqli->real_escape_string($_GET['id']);

$elements = array();

$elementsQuery = "select * from elements where page_id = ".$page_id;
$elementsResult = $mysqli->query($elementsQuery);

while($row = mysqli_fetch_array($elementsResult))
{
    $element = new stdClass();
    $element->id = $row['id'];
    $element->type = $row['type'];
    $element->sequence = $row['sequence'];
    $element->innerHtml = $row['inner_html'];
    $element->pageId = $row['page_id'];
    $element->attributes = array();
    $element->styles = array();

    $attributesQuery = "select * from element_attributes where element_id = ".$element->id;
    $attributesResult = $mysqli->query($attributesQuery);
    while($attributesRow = mysqli_fetch_array($attributesResult)){
        $attribute = new stdClass();
        $attribute->name = $attributesRow['attribute_name'];
        $attribute->value = $attributesRow['attribute_value'];
        array_push($element->attributes, $attribute);
    }

    $stylesQuery = "select * from element_styles where element_id = ".$element->id;
    $stylesResult = $mysqli->query($stylesQuery);
    while($stylesRow = mysqli_fetch_array($stylesResult)){
        $style = new stdClass();
        $style->attribute = $stylesRow['style_attribute'];
        $style->value = $stylesRow['style_value'];
        array_push($element->styles, $style);
    }
    array_push($elements, $element);
}

echo json_encode($elements);

?>