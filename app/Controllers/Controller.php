<?php

namespace App\Controllers;

class Controller
{
    /*
     * get data from all pages
     */
    public function __construct()
    {
        add_filter('timber_context', [$this, 'get_data']);
    }

    public function get_data($context) {
        // theme options
        // $data['data'] = carbon_get_theme_option('option');
        $context['is_home'] = is_page_template('page-home.php');
        $context['show_cookie_text'] = carbon_get_theme_option('show_cookie_text');
        $context['test'] = 'test123';
        // test posts
        // $data['test_posts'] = Timber::get_posts('post_type=test&numberposts=-1');

        // custom logo
        if ($custom_logo_id = get_theme_mod('custom_logo')) {
            $context['custom_logo'] = wp_get_attachment_image($custom_logo_id, 'full', false, [
                'class'    => 'custom-logo',
                'itemprop' => 'logo',
            ]);
        }

        add_action('breads_func', self::render_pagination());
        add_action('langs_func', self::render_langs());
        return $context;
    }

    /**
     * [render_pagination]
     * use {% do action('breads_func') %} in twig tpl to render pagination.
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

    /**
     * [render_langs]
     * use {% do action('langs_func') %} in twig tpl to render pagination.
     */
    public static function render_langs()
    {
        return function () {
            if (function_exists('pll_the_languages')) {
                $raw = pll_the_languages(['raw' => 1]);
                foreach ($raw as $lang) {
                    if ($lang['current_lang'] == 1) {
                        $act = 'active';
                    } else {
                        $act = '';
                    }
                    echo '<div class="language-item '.$act.'">';
                    echo '<a href="'.$lang['url'].'">';
                    echo $lang['slug'];
                    echo '</a>';
                    echo '</div>';
                }
            }
        };
    }
}


new Controller();
