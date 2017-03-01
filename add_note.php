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
         "module_name" => "AOP_Case_Updates",
         "name_value_list" => array(
              array("name" => "name", "value" => $_POST['title']),
              array("name" => "case_id", "value" => $_POST['case_id']),
         ),
    );

    $set_entry_result = call("set_entry", $set_entry_parameters, $url);

    echo json_encode($cases);
