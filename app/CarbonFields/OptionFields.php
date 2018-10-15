<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/*
 * theme option fields
 */
add_action('carbon_fields_register_fields', function(){

    Container::make('theme_options', 'Настройки темы')
        ->add_tab(__('Общее'), array(
            Field::make('text', 'sitename_1', 'Заголовок сайта'),
        ))->add_tab(__('Контакты'), array(
                Field::make('text', 'email_text', 'Email'),
                Field::make('text', 'tel_text', 'Телефон')
        ));

});
