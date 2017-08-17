<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action( 'carbon_fields_register_fields', 'crb_attach_post_options' );
function crb_attach_post_options() {

    // Container::make('post_meta', 'Дополнительные поля')
    //     ->show_on_post_type('novosti')
    //     ->add_fields(array(
    //         Field::make('rich_text', 'new_short_text', 'Описание на главной')
    // ));


}
