<?php

    require_once('functions.php');

    echo $session_id . "<br />";

    $gel_parameters = array(
        "session" => $session_id,
        "module_name" => "Cases",
        "query" => " cases.account_id = '93bbb4e2-e8ce-f526-5fb3-58b4e765f895' ",
 	"order_by" => "",
        "offset" => 0,
	"select_fields" => array(),
        "link_name_to_fields_array" => array(),
        "max_results" => 100,
        "deleted" => 0,
        "favorites" => false,
    );

    $gel_results = call("get_entry_list", $gel_parameters, $url);

    print_r($gel_results); 
    $case_name = $get_entry_result->entry_list[0]->name_value_list->name->value;
    $case_number = $get_entry_result->entry_list[0]->name_value_list->case_number->value;

    $cases = array('id' => $cid, 'name' => $case_name, 'number' => $case_number);

    //header('Content-type: application/json');
    //echo json_encode($cases);
