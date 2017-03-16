<?php

    require_once('functions.php');
    
    $suite = new SuiteCRM();
    $cases = $suite->add_case($_POST['title'], $_POST['body'], $_POST['account']);
    header('Content-type: application/json');
    echo json_encode($cases);
