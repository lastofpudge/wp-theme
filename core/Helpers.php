<?php

if (!function_exists('send_mail_cst')) {
    function send_mail_cst(string $filename, array $data)
    {
        $body = render_email_template($filename, $data);
        $sent = send_email($data['subject'], $body);
        return $sent ?: null;
    }

    function render_email_template(string $filename, array $data): string
    {
        ob_start();
        require __DIR__ . '/../views/emails/' . $filename . '.php';
        return ob_get_clean();
    }

    function send_email(string $subject, string $body): bool
    {
        $admin_email = get_bloginfo('admin_email');
        $headers[] = 'Content-type: text/html; charset=utf-8';
        return wp_mail($admin_email, $subject, $body, $headers);
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
