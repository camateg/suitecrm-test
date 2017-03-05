<?php

    require_once('functions.php');

     $ge_parameters = array(
        "session" => $session_id,
        "module_name" => "Documents",
        "id" => $_GET['document_id'],
        "select_fields" => array("document_revision_id"),
        'link_name_to_fields_array' => [],
    );

    $get_entry_results = call("get_entry", $ge_parameters, $url);

    $rev_id = $get_entry_results->entry_list[0]->name_value_list->document_revision_id->value;

    $get_doc_parameters = array(
        "session" => $session_id,
        "id" => $rev_id,
    );

    $get_doc_result = call("get_document_revision", $get_doc_parameters, $url);
   
    $file_name = $get_doc_result->document_revision->filename; 
    $file = $get_doc_result->document_revision->file;

    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file_name");
    header("Content-Transfer-Encoding: binary");

    echo base64_decode($file);        
