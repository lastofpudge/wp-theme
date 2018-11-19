<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/*
 * post/page fields
 */
add_action('carbon_fields_register_fields', function () {
    /*
     * HOME
     */
    require_once __DIR__.'/Models/home.php';
    require_once __DIR__.'/Models/about.php';
   // Container::make( 'comment_meta', 'Доп. поля' )
    //     ->add_fields( array(
    //         Field::make( 'text', 'comment_rating', 'Рейтинг' ),
    //     ) );
});
