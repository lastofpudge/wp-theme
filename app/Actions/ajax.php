<?php

header('Content-type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';

if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'testAction') {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            wp_send_json(['type' => 'error', 'message' => 'Ошибка nonce']);
        }

        $mail_subject = 'New order';
        require_once __DIR__.'/mailer__config.php';

        // load data
        $name = sanitize_text_field($_POST['user_name']);
        $mail = sanitize_text_field($_POST['user_mail']);
        $phone = '123';
        $message = '456';

        //validate data
        if (empty($name) || empty($mail)) {
            wp_send_json(['type' => 'error', 'message' => 'Вы не заполнили все обязательные поля']);
        }

        // wp_send_json(['type' => 'test', 'message' => $name. ' '. $mail ]);

        // create html letter
        ob_start();
        include __DIR__.'/Templates/testAction.php';
        $php_mailer->Body = ob_get_contents();
        ob_end_clean();

        if (!$php_mailer->send()) {
            wp_send_json(['type' => 'fail', 'message' => 'Fail send email']);
        }
        wp_send_json(['type' => 'success', 'message' => 'TEST']);
    }
}
