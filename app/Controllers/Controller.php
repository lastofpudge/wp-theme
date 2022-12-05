<?php

namespace App\Controllers;

use Closure;

class Controller
{
    public function __construct()
    {
        add_filter('timber_context', [$this, 'getData']);
    }

    public function getData(): array
    {
        $data = [];
        add_action('showBreads', self::renderBreads());
//        add_action('showLangs', self::renderLangs());

        return $data;
    }

    public static function renderBreads(): Closure
    {
        return function () {
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('<div class="breads__wrapper">', '</div>');
            } else {
                echo '<pre style="background-color: orange; padding:3px; color: #fff;">
                Plugin Yoast Seo is not active!</pre>';
            }
        };
    }

    public static function renderLangs(): Closure
    {
        return function () {
            if (function_exists('pll_the_languages')) {
                $raw = pll_the_languages(['raw' => 1]);

                $locale = str_replace('_', '-', get_locale());

                echo `<div class="langs__cover">
                <div class="langs__cover__inner">`;

                foreach ($raw as $lang) {
                    if ($lang['locale'] == $locale) {
                        echo `<a data-lang="{$lang['locale']}" class="button--lang lang--active" href="{$lang['url']}">
                                <img src="{$lang['flag']}">
                            </a>'`;
                    } else {
                        echo `<a data-lang="{$lang['locale']}" class="button--lang" href="{$lang['url']}">
                                <img src="{$lang['flag']}">
                            </a>`;
                    }
                }

                echo `</div></div>`;
            }
        };
    }
}
