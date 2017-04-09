<?php

namespace App\Controllers;

use Timber;

class Controller
{
    /*
     * get data from all pages
     */
    public static function getData()
    {
        $data = Timber::get_context();
        global $redux_opt;
        $data['test'] = '1';

        add_action('breads_func', self::render_pagination());

        return $data;
    }

    /**
     * [render_pagination]
     * use {% do action('breads_func') %} in twig tpl to render pagination
     */
    public static function render_pagination()
    {
        return function () {
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('', '');
            } else {
                echo '<pre style="background-color: orange; padding:3px; color: #fff;">Plugin Youst Seo is not active!</pre>';
            }
        };
    }
}
