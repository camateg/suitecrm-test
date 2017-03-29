<?php
    require_once('functions.php');
        $_SESSION['user_id'] = '';
        $_SESSION['user_name'] = '';
        $_SESSION['user_hash'] = '';
    session_destroy();
    header( 'Location: ./' );
