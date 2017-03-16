<?php

    require_once('functions.php');

    $suite = new SuiteCRM();
    $download = $suite->download($_GET['document_id']);
    $file_name = $download['file_name'];

    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file_name");
    header("Content-Transfer-Encoding: binary");

    echo base64_decode($download['file']);        
