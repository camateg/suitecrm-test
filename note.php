<?php

    require_once('functions.php');

    $suite = new SuiteCRM();
    $note = $suite->add_note($_POST['name'], $_POST['case_id']);
    header('Content-type: application/json');
    echo json_encode($note);    
