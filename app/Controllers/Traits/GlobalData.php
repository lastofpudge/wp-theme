<?php

namespace App\Controllers\Traits;

use Timber;
use Redux;
use TimberHelper;

/*
 * data from all pages
 */
trait GlobalData
{
    /*
     * get data from all pages
     */
    public static function getData()
    {
        $data = Timber::get_context();
        global $redux_opt;
        // $data['gp'] = carbon_get_theme_option('crb_google_url');
        // $data['menu'] = TimberHelper::function_wrapper('wp_nav_menu', array('theme_location' => 'header_menu', 'container'=> false, 'menu_class'=> false));

        add_action('breads_func', self::render_pagination());

        return $data;
    }

    /*
     * pagination
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
