<?php

use Timber\Timber;

if (!function_exists('send_mail_cst')) {
    function send_mail_cst(string $filename, array $data): ?bool
    {
        $body = render_email_template($filename, $data);
        $sent = send_email($data['subject'], $body);

        return $sent ?: null;
    }

    function render_email_template(string $filename, array $data): string
    {
        $compiled = Timber::compile('/resources/views/emails/' . $filename . '.twig', $data);

        return $compiled;
    }

    function send_email(string $subject, string $body): bool
    {
        if ($_ENV['MAIL_FROM_ADDRESS']) {
            $from_email = $_ENV['MAIL_FROM_ADDRESS'];
        } else {
            $from_email = get_bloginfo('admin_email');
        }

        if ($_ENV['MAIL_TO_ADDRESS']) {
            $to_email = $_ENV['MAIL_TO_ADDRESS'];
        } else {
            $to_email = get_bloginfo('admin_email');
        }

        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: ' . $from_email;

        $response = wp_mail($to_email, $subject, $body, $headers);

        return $response;
    }
}

add_action('wp_mail_failed', function ($wp_error) {
    wp_send_json(['type' => 'false', 'sended' => $wp_error]);
});

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
