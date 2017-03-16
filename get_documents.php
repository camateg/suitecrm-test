<?php

    require_once('functions.php');
    $suite = new SuiteCRM();
    $documents = $suite->get_documents($_GET['case_id']);  
    header('Content-type: application/json');
     echo json_encode($documents);
