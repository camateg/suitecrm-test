<?php

    require_once('functions.php');

    $suite = new SuiteCRM();
    $accounts = $suite->get_accounts($_SESSION['user_id']);    
    header('Content-type: application/json');
    echo json_encode($accounts);
