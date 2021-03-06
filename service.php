<?php

    require_once('functions.php');

    /*
      This is a sort of wrapper for several common
      services / functions used in the SuiteCRM class.
    */

    $action = $_GET['action'];
    $param = $_GET['param'];
    $suite = new SuiteCRM();
    $results = call_user_func_array(array($suite, $action), array($param));
    header('Content-type: application/json');
    echo json_encode($results);
