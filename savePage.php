<?php

header("Access-Control-Allow-Origin: *");
include "dbconfig.php";

session_start();

if($_SESSION["isLoggedIn"] || true){
    $data = json_decode(file_get_contents('php://input'));

    if($data->parent_page_id != null){
        $addPageQuery = "insert into pages (title, parent_page_id) values ('"
        .$data->title."', '".$data->parent_page_id."')";
        $pageAddResult = $mysqli->query($addPageQuery);
    } else {
        $addPageQuery = "insert into pages (title) values ('"
                        .$data->title."')";
        $pageAddResult = $mysqli->query($addPageQuery);
    }

    if($pageAddResult){
        $newPageData = new stdClass();

        $pageQuery = "select * from pages order by id desc limit 1";
        $pageResult = $mysqli->query($pageQuery);
        $row = mysqli_fetch_array($pageResult);
        $newPageData->id = $row['id'];
        $newPageData->title = $row['title'];

        //Load in all elements
        for($x = 0; $x < count($data->elements); $x++){
            //START ADD ELEMENT
            $data->elements[$x]->sequence = 0;
            $addElementQuery = "insert into elements (type, sequence, inner_html, page_id) values ('"
                                .$data->elements[$x]->type."', '"
                                .$data->elements[$x]->sequence."', '"
                                .$data->elements[$x]->innerHTML."', '"
                                .$newPageData->id."')";

            $elementAddResult = $mysqli->query($addElementQuery);

            if($elementAddResult){
                $elementIdQuery = "select id from elements order by id desc limit 1";
                $elementId = mysqli_fetch_array($mysqli->query($elementIdQuery))['id'];

                // add all attributes
                if(is_array($data->elements[$x]->attributes)){
                    for($i = 0; $i < count($data->elements[$x]->attributes); $i++){
                        $addAttributeQuery = "insert into element_attributes (element_id, attribute_name, attribute_value) values ('"
                                            .$elementId."', '"
                                            .$data->elements[$x]->attributes[$i]->name."', '"
                                            .$data->elements[$x]->attributes[$i]->value."')";
                        $mysqli->query($addAttributeQuery);
                    }
                }

                // add all styles
                if(is_array($data->elements[$x]->styles)){
                    for($i = 0; $i < count($data->elements[$x]->styles); $i++){
                        $addStyleQuery = "insert into element_styles (element_id, style_attribute, style_value) values ('"
                                            .$elementId."', '"
                                            .$data->elements[$x]->styles[$i]->attribute."', '"
                                            .$data->elements[$x]->styles[$i]->value."')";
                        $mysqli->query($addStyleQuery);
                    }
                }

                //UPDATE CHILDREN
                $updatePageQuery = "update pages set parent_page_id = '"
                        .$newPageData->id."' where parent_page_id = '".$data->id."'";
    
                $pageUpdateResult = $mysqli->query($updatePageQuery);

                //DELETE OLD PAGE
                $page_id = $data->id;
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
                $errorData->message = "Error adding element";
                echo json_encode($errorData);
            }
            //END ADD ELEMENT
        }

        echo json_encode($newPageData);
    }
} else {
    $errorData = new stdClass();
    $errorData->message = "Invalid credentials.";
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