<?php

    require_once('functions.php');

    $gel_parameters = array(
         "session" => $session_id,
         "module_name" => "Contacts",
         "query" => " contacts_cstm.portal_user_c = '" . $_POST['user'] . "'",
         "order_by" => "",
         "offset" => 0,
         "select_fields" => array(),
         "link_name_to_fields_array" => array(),
         "max_results" => 1,
         "deleted" => 0,
         "favorites" => false,
    );

    $gel_results = call("get_entry_list", $gel_parameters, $url);

    $contacts = [];

    foreach($gel_results->entry_list as $entry) {
      $id = $entry->name_value_list->id->value;
      $name = $entry->name_value_list->name->value;
      $portal_md5 = $entry->name_value_list->portal_md5_c->value;
      $portal_user = $entry->name_value_list->portal_user_c->value;
      $contacts[]= array("id" => $id, "name" => $name, "portal_user" => $portal_user, "portal_md5" => $portal_md5);
    }

    if ($portal_md5 == md5($_POST['password'])) {
      $_SESSION['user_id'] = $id;
      header( 'Location: show_cases.php' ) ;
    } else {
      session_destroy();
      header( 'Location: ./?error=Password%20Incorrect' );
    }
