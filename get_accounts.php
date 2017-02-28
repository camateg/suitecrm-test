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

    $gel_parameters = array(
         "session" => $session_id,
         "module_name" => "Accounts",
         "query" => "",
         "order_by" => "",
         "offset" => 0,
         "select_fields" => array(),
         "link_names_to_field_array" => array(),
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
