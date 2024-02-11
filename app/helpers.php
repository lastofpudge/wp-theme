<?php

use Timber\Timber;

if (!function_exists('send_email')) {
    /**
     * Send a custom email.
     * @param  string  $templateFilename
     * @param  array  $templateData
     * @return bool|null
     */
    function send_email(string $templateFilename, array $templateData): ?bool
    {
        $emailBody = compile_email_template($templateFilename, $templateData);
        $isSent = dispatch_email($templateData['subject'], $emailBody);

        return $isSent ?: null;
    }

    /**
     * Compile an email template.
     * @param  string  $filename
     * @param  array  $data
     * @return string
     */
    function compile_email_template(string $filename, array $data): string
    {
        return Timber::compile('/resources/views/emails/'.$filename.'.twig', $data);
    }

    /**
     * Dispatch an email.
     * @param  string  $subject
     * @param  string  $body
     * @return bool
     */
    function dispatch_email(string $subject, string $body): bool
    {
        $adminEmail = get_option('admin_email');
        $fromEmail = $adminEmail;
        $toEmail = $adminEmail;

        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: '.$fromEmail;

        return wp_mail($toEmail, $subject, $body, $headers);
    }
}

if (!function_exists('add_ajax_action')) {
    /**
     * Registers an AJAX action with WordPress.
     * @param  string  $name  The name of the AJAX action.
     */
    function add_ajax_action(string $name): void
    {
        $action_path = APP_PATH.'/Handlers/AjaxHandlers/'.$name.'.php';
        add_ajax_action_impl($name, 'wp_ajax', $action_path);
        add_ajax_action_impl($name, 'wp_ajax_nopriv', $action_path);
    }

    /**
     * Helper function to implement AJAX action registration.
     * @param  string  $name  The name of the AJAX action.
     * @param  string  $hook  The WordPress hook to associate with the action.
     * @param  string  $action_path  Path to the PHP file that handles the action.
     */
    function add_ajax_action_impl(string $name, string $hook, string $action_path): void
    {
        add_action($hook."_$name", function () use ($action_path) {
            require $action_path;
        });
    }
}

if (!function_exists('dd')) {
    /**
     * Debug function to dump and die. Outputs the given variable and stops execution.
     * @param  mixed  $result  The variable to be dumped.
     */
    function dd(mixed $result): void
    {
        echo '<pre>';
        print_r($result);
        die();
    }
}

if (!function_exists('crb_get_i18n_suffix')) {
    /**
     * Get the suffix for internationalization, typically a language code.
     * @return string The suffix for the current language, or an empty string if not set.
     */
    function crb_get_i18n_suffix(): string
    {
        $suffix = '';
        if (!defined('ICL_LANGUAGE_CODE')) {
            return $suffix;
        }
        return '_'.ICL_LANGUAGE_CODE;
    }
}

if (!function_exists('crb_get_i18n_theme_option')) {
    /**
     * Retrieves a theme option value with internationalization support.
     * @param  string  $option_name  The name of the theme option.
     * @return mixed The value of the theme option for the current language.
     */
    function crb_get_i18n_theme_option(string $option_name)
    {
        $suffix = crb_get_i18n_suffix();
        return carbon_get_theme_option($option_name.$suffix);
    }
}
