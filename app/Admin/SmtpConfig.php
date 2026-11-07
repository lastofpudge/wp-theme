<?php

namespace App\Admin;

class SmtpConfig
{
    public function __construct()
    {
        add_action('phpmailer_init', [$this, 'configureSMTP']);
    }

    public function configureSMTP(\PHPMailer\PHPMailer\PHPMailer $phpmailer): void
    {
        $phpmailer->isSMTP();

        $phpmailer->Host = SMTP_HOST;
        $phpmailer->Port = SMTP_PORT;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = SMTP_USER;
        $phpmailer->Password = SMTP_PASS;
        $phpmailer->SMTPSecure = SMTP_SECURE;
        $phpmailer->From = defined('SMTP_FROM') ? SMTP_FROM : SMTP_USER;
        $phpmailer->FromName = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : get_bloginfo('name');
        $phpmailer->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];
    }
}
