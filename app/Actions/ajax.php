<?php

header('Content-type: text/html; charset=utf-8');

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-load.php')) {
    include_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
} else {
    $root = explode('/wp-content/', dirname(__FILE__));
    include_once str_replace('wp-content/', '', $root[0].'/wp-load.php');
}

if (isset($_POST['action']) && !empty($_POST['action']) && !is_admin()) {
    $action = $_POST['action'];
    require_once __DIR__.'/../../config/mail.php';

    if ($action === 'testAction') {
        require_once __DIR__.'/mails/testActionMail.php';
    }
}
