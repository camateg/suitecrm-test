<?php

    require_once('functions.php');

    //login --------------------------------------------- 

    $login_parameters = array(
         "user_auth" => array(
              "user_name" => $username,
              "password" => md5($password),
              "version" => "1"
         ),
         "application_name" => "mCasePortal",
         "name_value_list" => array(),
    );

    $login_result = call("login", $login_parameters, $url);

    $session_id = $login_result->id;

    $set_entry_parameters = array(
         "session" => $session_id,
         "module_name" => "Cases",
         "name_value_list" => array(
              array("name" => "name", "value" => $_POST['title']),
              array("name" => "description", "value" => $_POST['body']),
              array("name" => "account_id", "value" => $_POST['account']),
         ),
    );

    $set_entry_result = call("set_entry", $set_entry_parameters, $url);

    $cid = $set_entry_result->id;

    $get_entry_parameters = array(
        "session" => $session_id,
        "module_name" => "Cases",
        "id" => $cid,
        "select_fields" => array(
	    "id",
            "name",
            "case_number",
        ),
        'link_name_to_fields_array' => [],
    );

    $get_entry_result = call("get_entry", $get_entry_parameters, $url);
    
    $case_name = $get_entry_result->entry_list[0]->name_value_list->name->value;
    $case_number = $get_entry_result->entry_list[0]->name_value_list->case_number->value;

    $cases = array('id' => $cid, 'name' => $case_name, 'number' => $case_number);

    header('Content-type: application/json');
    echo json_encode($cases);
