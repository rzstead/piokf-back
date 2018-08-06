<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

$data = json_decode(file_get_contents('php://input'));

$addElementQuery = "insert into elements (type, sequence, inner_html, page_id) values ('"
                    .$data->type."', '"
                    .$data->sequence."', '"
                    .$data->inner_html."', '"
                    .$data->page_id."')";

$elementAddResult = $mysqli->query($addElementQuery);

if($elementAddResult){
    $elementIdQuery = "select id from elements order by id desc limit 1";
    $elementId = mysqli_fetch_array($mysqli->query($elementIdQuery))['id'];

    echo $elementId;

    // add all attributes
    for($i = 0; $i < count($data->attributes); $i++){
        $addAttributeQuery = "insert into element_attributes (element_id, attribute_name, attribute_value) values ('"
                            .$elementId."', '"
                            .$data->attributes[$i]->name."', '"
                            .$data->attributes[$i]->value."')";
        $mysqli->query($addAttributeQuery);
    }

    // add all styles
    for($i = 0; $i < count($data->styles); $i++){
        $addStyleQuery = "insert into element_styles (element_id, style_attribute, style_value) values ('"
                            .$elementId."', '"
                            .$data->styles[$i]->attribute."', '"
                            .$data->styles[$i]->value."')";
        $mysqli->query($addStyleQuery);
    }
} else {
    echo "ERROR ADDING ELEMENT";
}

/*
    EXAMPLE JSON EXPECTED:
    {
        "type":"a",
        "sequence":"3",
        "inner_html":"Test Link",
        "page_id":"4",
        "attributes":[
            {
                "name":"href",
                "value":"test.html"
            }
        ],
        "styles":[
            {
                "attribute":"font-size",
                "value":"2em"
            },
            {
                "attribute":"color",
                "value":"#FE239C"
            }
        ]
    }
*/

?>