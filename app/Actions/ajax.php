<?php
ini_set('display_errors', '1');
header('Content-type: text/html; charset=utf-8');

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

if (!empty($_POST))
{
    $action = $_POST['action'];

    if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) { wp_send_json([ 'type' => 'error', 'message' => 'Ошибка nonce',]); }
    require_once __DIR__.'/mailer-config.php';

    if ($action == "testAction")
    {
        // load data
        $name = sanitize_text_field($_POST['user_name']);
        $mail = sanitize_text_field($_POST['user_mail']);
        $phone = '123';
        $message = '456';

        //validate data
        if (empty($name) || empty($mail)) { wp_send_json(['type' => 'error', 'message' => 'Вы не заполнили все обязательные поля',]); }

        // testdata
        wp_send_json(['type' => 'test', 'message' => $name. ' '. $mail ]);

        //send mail
        $email_to = get_bloginfo('admin_email');
        $site_name = get_bloginfo( 'name' );

        $php_mailer->addAddress($email_to, 'SiteTitle');
        $php_mailer->setFrom = $site_name;
        $php_mailer->From = $email_to;
        $php_mailer->FromName = $site_name." Support";
        $php_mailer->Subject = "New order ".$site_name;

        // create html letterm
        ob_start();
            include(__DIR__.'/Templates/testAction.php');
            $php_mailer->Body = ob_get_contents();
        ob_end_clean();

        // send html letter
        if(!$php_mailer->send()){ wp_send_json(['type' => 'fail','message' => 'Fail send email']); }
        wp_send_json(['type' => 'success','message' => 'TEST']);
    }

}
