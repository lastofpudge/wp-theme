<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/*
 * theme option fields
 */
add_action('carbon_fields_register_fields', function ()
{
    Container::make('theme_options', 'Настройки темы')
        ->add_tab(__('Общее'), [
            Field::make('text', 'sitename_1', 'Заголовок сайта'),
            Field::make('checkbox', 'show_cookie_text', 'Показывать текс для cookie'),
        ])->add_tab(__('Контакты'), [
                Field::make('text', 'email_text', 'Email'),
                Field::make('text', 'tel_text', 'Телефон'),
        ]);
});
