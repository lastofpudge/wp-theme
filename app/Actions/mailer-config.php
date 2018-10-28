<?php
    ini_set('display_errors', '1');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $php_mailer = new PHPMailer;
    $php_mailer->isSMTP();
    $php_mailer->SMTPAuth = true;
    $php_mailer->SMTPSecure = 'tls';

    $php_mailer->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $php_mailer->CharSet = "utf-8";
    // $php_mailer->SMTPDebug = 2;

    $php_mailer->Username   = "";
    $php_mailer->Password   = "";

    $php_mailer->Host       = 'smtp.gmail.com';
    $php_mailer->Port       = 587;
