<?php
    require_once('functions.php');
    $logout_parameters = array(
       "session" => $session_id
    );
    $login_result = call("logout", $logout_parameters, $url);
    session_destroy();
    header( 'Location: ./' );
