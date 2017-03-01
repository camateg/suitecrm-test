<?php

    require_once('functions.php');

    $ge_parameters = array(
        "session" => $session_id,
        "module_name" => "Cases",
        "id" => $_GET['case_id'],
        "select_fields" => array(),
        'link_name_to_fields_array' => [],
    );

    $ge_result = call("get_entry", $ge_parameters, $url);
   
    //print_r($ge_result);
    print_r($get_entry_result->entry_list[0]); 
    $case_name = $ge_result->entry_list[0]->name_value_list->name->value;
    $case_number = $ge_result->entry_list[0]->name_value_list->case_number->value;
    $case_description = $ge_result->entry_list[0]->name_value_list->description->value;

    $gel_parameters = array(
      "session" => $session_id,
      "module_name" => "AOP_Case_Updates",
      "query" => " aop_case_updates.case_id = '" . $_GET['case_id'] . "' ",
      "order_by" => "",
      "offset" => "",
      "select_fields" => array(),
      "link_name_to_fields_array" => [],
      "max_results" => 10,
      "deleted" => 0,
      "favorites" => false,
    );

    $gel_results = call("get_entry_list", $gel_parameters, $url);

    $notes = [];

    foreach($gel_results->entry_list as $entry) {
      $id = $entry->name_value_list->id->value;
      $name = $entry->name_value_list->name->value;
      $desc = $entry->name_value_list->description->value;
      $notes[] = array("id" => $id, "name" => $name, "description" => $desc);
    };

    $case_info = array("case_name" => $case_name, "case_number" => $case_number, "case_description" => $case_description, "notes" => $notes);

    header('Content-type: application/json');
    echo json_encode($case_info);
