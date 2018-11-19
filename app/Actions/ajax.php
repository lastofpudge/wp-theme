<?php

header('Content-type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';

if (isset($_POST['action']) && !empty($_POST['action']) && !is_admin()) {
    $action = $_POST['action'];
    require_once __DIR__.'/../../config/mail.php';

    if ($action === 'testAction') {
        require_once __DIR__.'/mails/testActionMail.php';
    }
}
