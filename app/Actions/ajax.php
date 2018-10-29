<?php

header('Content-type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';

if (isset($_POST['action']) && !empty($_POST['action']) && !is_admin()) {
    $action = $_POST['action'];

    if ($action === 'testAction') {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            wp_send_json(['type' => 'error', 'message' => 'Ошибка nonce']);
        }

        $mail_subject = 'New order';
        require_once __DIR__.'/../../config/mail.php';

        $name = sanitize_text_field($_POST['user_name']);
        $mail = sanitize_text_field($_POST['user_mail']);
        //validate data
        if (empty($name) || empty($mail)) {
            wp_send_json(['type' => 'error', 'message' => 'Вы не заполнили все обязательные поля']);
        }

        // wp_send_json(['type' => 'test', 'message' => $name. ' '. $mail ]);
        $site_name = get_bloginfo('name');
        loadMail('testMail', [
            'php_mailer' => $php_mailer,
            'site_name'  => $site_name,
            'name'       => $name,
            'mail'       => $mail,
            'phone'      => '09912313',
            'message'    => 'message test',
        ]);

        if (!$php_mailer->send()) {
            wp_send_json(['type' => 'fail', 'message' => 'Fail send email']);
        }
        wp_send_json(['type' => 'success', 'message' => 'TEST']);
    }


}
