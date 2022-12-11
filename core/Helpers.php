<?php

if (!function_exists('crb_get_i18n_suffix')) {
    function crb_get_i18n_suffix(): string
    {
        if (!defined('ICL_LANGUAGE_CODE')) {
            return '';
        }

        return '_' . ICL_LANGUAGE_CODE;
    }
}

/*
 * Translate string
 */
if (!function_exists('crb_get_i18n_theme_option')) {
    function crb_get_i18n_theme_option($option_name)
    {
        $suffix = crb_get_i18n_suffix();

        return carbon_get_theme_option($option_name . $suffix);
    }
}

/*
 * Write log helper
 */
if (!function_exists('write_log')) {
    function write_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

if (!function_exists('send_mail_cst')) {
    function send_mail_cst($filename, $data)
    {
        ob_start();
        require_once __DIR__ . '/../views/emails/' . $filename . '.php';
        $body = ob_get_contents();
        ob_end_clean();

        $admin_email = get_bloginfo('admin_email');
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $sent = wp_mail($admin_email, $data['subject'], $body, $headers);

        if ($sent) {
            return $sent;
        }

        return null;
    }
}

add_action(
    'wp_mail_failed',
    function ($wp_error) {
        wp_send_json([
            'type' => 'false',
            'sended' => $wp_error,
        ]);
    },
    10,
    1
);

if (!function_exists('add_ajax_action')) {
    function add_ajax_action($name)
    {
        add_action("wp_ajax_$name", function () use ($name) {
            require_once APP_PATH . '/Actions/notification/' . $name . '.php';
        });

        add_action("wp_ajax_nopriv_$name", function () use ($name) {
            require_once APP_PATH . '/Actions/notification/' . $name . '.php';
        });
    }
}
