<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/*
 * theme option fields
 */
add_action('carbon_fields_register_fields', function () {
    Container::make('theme_options', 'Theme options')
    ->add_tab(__('Global'), [Field::make('text', 'site_name', 'Site title')])
    ->add_tab(__('Contacts'), [Field::make('text', 'email_text', 'Email'), Field::make('text', 'tel_text', 'Phone')]);
});
