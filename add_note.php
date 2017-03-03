<?php

    require_once('functions.php');

    $set_entry_parameters = array(
         "session" => $session_id,
         "module_name" => "AOP_Case_Updates",
         "name_value_list" => array(
              array("name" => "name", "value" => $_POST['name']),
              array("name" => "description", "value" => $_POST['name']),
              array("name" => "case_id", "value" => $_POST['case_id']),
              array("name" => "assigned_user_id", "value" => $portal_user),
         ),
    );

    $set_entry_result = call("set_entry", $set_entry_parameters, $url);
