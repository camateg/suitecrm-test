<?php

    require_once('functions.php');

    $set_entry_parameters = array(
         "session" => $session_id,
         "module_name" => "Documents",
         "name_value_list" => array(
              array("name" => "document_name", "value" => $_POST['name']),
         ),
    );

    $set_entry_result = call("set_entry", $set_entry_parameters, $url);

    $did = $set_entry_result->id;

    $cid = $_POST['case_id'];

    

    header('Content-type: application/json');
    echo json_encode($cases);
