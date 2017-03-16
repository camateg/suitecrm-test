<?php

    require_once('functions.php');
        function date_compare($a, $b)
        {   
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        }
    
    $suite = new SuiteCRM();
    $notes = $suite->get_notes($_GET['case_id']);
    usort($notes, 'date_compare');


    header('Content-type: application/json');
    echo json_encode($notes);
