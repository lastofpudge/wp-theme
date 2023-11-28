<?php

use Timber\Timber;

if (!function_exists('send_custom_mail')) {
    function send_custom_mail(string $templateFilename, array $templateData): ?bool
    {
        $emailBody = compile_email_template($templateFilename, $templateData);
        $isSent = dispatch_email($templateData['subject'], $emailBody);

        return $isSent ?: null;
    }

    function compile_email_template(string $filename, array $data): string
    {
        $compiledTemplate = Timber::compile('/resources/views/emails/' . $filename . '.twig', $data);

        return $compiledTemplate;
    }

    function dispatch_email(string $subject, string $body): bool
    {
        $adminEmail = get_option('admin_email');

        $fromEmail = $_ENV['MAIL_FROM_ADDRESS'] ?? $adminEmail;
        $toEmail = $_ENV['MAIL_TO_ADDRESS'] ?? $adminEmail;

        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: ' . $fromEmail;

        $isEmailSent = wp_mail($toEmail, $subject, $body, $headers);

        return $isEmailSent;
    }
}

if (!function_exists('add_ajax_action')) {
    function add_ajax_action(string $name)
    {
        $action_path = APP_PATH . '/Actions/notification/' . $name . '.php';
        add_ajax_action_impl($name, 'wp_ajax', $action_path);
        add_ajax_action_impl($name, 'wp_ajax_nopriv', $action_path);
    }

    function add_ajax_action_impl(string $name, string $hook, string $action_path)
    {
        add_action($hook . "_$name", function () use ($action_path) {
            require $action_path;
        });
    }
}
