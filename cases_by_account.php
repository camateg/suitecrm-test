<?php

    require_once('functions.php');

    function number_compare($a, $b)
        {
            $t2 = $a['number'];
            $t1 = $b['number'];
            return $t1 - $t2;
        }
    $gel_parameters = array(
        "session" => $session_id,
        "module_name" => "Cases",
        "query" => " cases.account_id = '" . $_GET['account_id'] . "' ",
 	"order_by" => " cases.case_number DESC ",
        "offset" => 0,
	"select_fields" => array(),
        "link_name_to_fields_array" => array(),
        "max_results" => 100,
        "deleted" => 0,
        "favorites" => false,
    );

    $gel_results = call("get_entry_list", $gel_parameters, $url);

    $cases = [];

    foreach($gel_results->entry_list as $entry) {
      $id = $entry->name_value_list->id->value;
      $name = $entry->name_value_list->name->value;
      $number = $entry->name_value_list->case_number->value;
      $cases[]= array("id" => $id, "name" => $name, "number" => $number);
    }

    usort($cases, 'number_compare');

    header('Content-type: application/json');
    echo json_encode($cases);
