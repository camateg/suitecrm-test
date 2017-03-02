<?php

    require_once('functions.php');
    
    $ger_params = array(
         'session'=>$session_id,
         'module_name' => 'Contacts',
         'module_id' => $_SESSION['user_id'],
         'link_field_name' => 'accounts',
         'related_module_query' => '',
         'related_fields' => array(
            'id',
            'name',
         ),
         'related_module_link_name_to_fields_array' => array(
         ),
         'deleted'=> '0',
         'order_by' => '',
         'offset' => 0,
         'limit' => 5,
    );

    $ger_result = call("get_relationships", $ger_params, $url);

    $accounts = [];

    foreach($ger_result->entry_list as $entry) {
      $id = $entry->name_value_list->id->value;
      $name = $entry->name_value_list->name->value;
      $accounts[]= array("id" => $id, "name" => $name);
    }

    header('Content-type: application/json');
    echo json_encode($accounts);
