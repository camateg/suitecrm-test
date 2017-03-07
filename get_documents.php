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
