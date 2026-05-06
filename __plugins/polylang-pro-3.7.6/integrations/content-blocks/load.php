<?php

if (!defined('ABSPATH')) {
    exit; // Don't access directly.
}

add_action(
    'plugins_loaded',
    function () {
        if (function_exists('custom_post_widget_plugin_init')) {
            add_action('pll_init', [PLL_Integrations::instance()->content_blocks = new PLL_Content_Blocks(), 'init']);
        }
    },
    0
);
