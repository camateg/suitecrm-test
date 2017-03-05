<?php

    require_once('functions.php');
        function date_compare($a, $b)
        {   
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        }

    $gr_parameters = array(
        "session" => $session_id,
        "module_name" => "Cases",
        "module_id" => $_GET['case_id'],
        "link_field_name" => "documents",
        "related_module_query" => "",
        "related_fields" => array('id', 'name'),
        "related_module_link_name_to_fields_array" => array(),
        "deleted" => 0,
        "order_by" => "",
        "offset" => 0,
        "limit" => 10,
     );

     $grr = call("get_relationships", $gr_parameters, $url);

     $documents = [];

     foreach($grr->entry_list as $entry) {
       $id = $entry->id;
       $document_name = $entry->name_value_list->name->value;
       $documents[]= array("id" => $id, "document_name" => $document_name); 
     }

     header('Content-type: application/json');
     echo json_encode($documents);
/*
     $ge_parameters = array(
        "session" => $session_id,
        "module_name" => "Documents",
        "id" => $_GET['case_id'],
        "select_fields" => array(),
        'link_name_to_fields_array' => [],
    );

    $ge_result = call("get_entry", $ge_parameters, $url);
   
    $case_name = $ge_result->entry_list[0]->name_value_list->name->value;
    $case_number = $ge_result->entry_list[0]->name_value_list->case_number->value;
    $case_description = $ge_result->entry_list[0]->name_value_list->description->value;

    $gel_parameters = array(
      "session" => $session_id,
      "module_name" => "AOP_Case_Updates",
      "query" => " aop_case_updates.case_id = '" . $_GET['case_id'] . "' ",
      "order_by" => "aop_case_updates.date_entered DESC",
      "offset" => "",
      "select_fields" => array(),
      "link_name_to_fields_array" => [],
      "max_results" => 40,
      "deleted" => 0,
      "favorites" => false,
    );

    $gel_results = call("get_entry_list", $gel_parameters, $url);

    $notes = [];

    foreach($gel_results->entry_list as $entry) {
      $id = $entry->name_value_list->id->value;
      $name = $entry->name_value_list->name->value;
      $desc = $entry->name_value_list->description->value;
      $date_entered = $entry->name_value_list->date_entered->value;
      $assigned_user_id = $entry->name_value_list->assigned_user_id->value;
      if ($assigned_user_id == $portal_user) {
         $portal = 1;
      } else {
         $portal = 0;
      }
      $notes[] = array("id" => $id, "name" => $name, "description" => $desc, "date" => $date_entered, "portal" => $portal);
    };

    usort($notes, 'date_compare');

    $cid = $_GET['case_id'];

    header('Content-type: application/json');
    echo json_encode($notes);

*/
