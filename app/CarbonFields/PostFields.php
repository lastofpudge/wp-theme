<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/*
 * post/page fields
 */
add_action('carbon_fields_register_fields', function(){

    // Container::make('post_meta', 'Дополнительные поля')
    //     ->show_on_post_type('post')
    //     ->add_fields(array(
    //         Field::make('rich_text', 'new_short_text', 'Описание на главной')
    // ));

});
