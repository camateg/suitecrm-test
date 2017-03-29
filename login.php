<?php
    require_once('functions.php');

    $suite = new SuiteCRM();

    $user_hash = md5($_POST['password']);

    $user_id = $suite->portal_login($_POST['user'], $user_hash);    

    if ($user_id == -1) {
        header( 'Location: ./?error=Password%20Incorrect' );
    } else {
	$_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $_POST['user']; 
	$_SESSION['user_hash'] = $user_hash;
        header( 'Location: ./show_cases.php');
    }
