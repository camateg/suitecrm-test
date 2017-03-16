<?php
    require_once('functions.php');

/*        $_SESSION['user_id'] = '';
        $_SESSION['user_name'] = '';
        $_SESSION['user_pass'] = '';
 */   session_destroy();
    header( 'Location: ./' );
