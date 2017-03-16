<?php

    require_once('functions.php');

    function number_compare($a, $b)
        {
            $t2 = $a['number'];
            $t1 = $b['number'];
            return $t1 - $t2;
        }

    $suite = new SuiteCRM();
    $cases = $suite->cases_by_account($_GET['account_id']);
    usort($cases, 'number_compare');

    header('Content-type: application/json');
    echo json_encode($cases);
