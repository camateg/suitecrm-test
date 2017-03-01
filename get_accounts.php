<?php

    require_once('functions.php');

    $gel_parameters = array(
         "session" => $session_id,
         "module_name" => "Accounts",
         "query" => "",
         "order_by" => "",
         "offset" => 0,
         "select_fields" => array(),
         "link_name_to_fields_array" => array(),
         "max_results" => 10,
         "deleted" => 0,
         "favorites" => false,
    );

    $gel_results = call("get_entry_list", $gel_parameters, $url);

    $accounts = [];

    foreach($gel_results->entry_list as $entry) {
      $id = $entry->name_value_list->id->value;
      $name = $entry->name_value_list->name->value;
      $accounts[]= array("id" => $id, "name" => $name);
    }

    header('Content-type: application/json');
    echo json_encode($accounts);
