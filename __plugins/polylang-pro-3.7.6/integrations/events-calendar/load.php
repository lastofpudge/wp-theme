<?php

if (!defined('ABSPATH')) {
    exit; // Don't access directly.
}

add_action(
    'plugins_loaded',
    function () {
        if (defined('TRIBE_EVENTS_FILE')) {
            add_action('pll_init', [PLL_Integrations::instance()->tec = new PLL_TEC(), 'init']);
        }
    },
    0
);
