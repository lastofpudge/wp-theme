<?php

if (!defined('ABSPATH')) {
    exit; // Don't access directly.
}

add_action(
    'plugins_loaded',
    function () {
        if (defined('CPTUI_VERSION')) {
            add_action('pll_init', [PLL_Integrations::instance()->cptui = new PLL_CPTUI(), 'init']);
        }
    },
    0
);
