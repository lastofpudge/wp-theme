<?php

if (!defined('ABSPATH')) {
    exit; // Don't access directly.
}

add_action(
    'after_setup_theme',
    function () {
        if (did_action('pll_init') && (defined('AC_FILE') || defined('ACP_FILE'))) {
            add_action('admin_init', [PLL_Integrations::instance()->cpac = new PLL_CPAC(), 'init']);
        }
    }
);
