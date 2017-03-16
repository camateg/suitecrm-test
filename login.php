<?php
    require_once('functions.php');

    $suite = new SuiteCRM();

    $user_id = $suite->portal_login($_POST['user'], $_POST['password']);    

    if ($user_id == -1) {
        header( 'Location: ./?error=Password%20Incorrect' );
    } else {
	$_SESSION['user_id'] = $user_id;
        header( 'Location: ./show_cases.php');
    }
