<?php

/*
 * post/page fields
 */
add_action('carbon_fields_register_fields', function () {
    include __DIR__.'/Models/home.php';
    include __DIR__.'/Models/about.php';
    // Container::make( 'comment_meta', 'Add. fields' )
    //     ->add_fields( array(
    //         Field::make( 'text', 'comment_rating', 'Rating' ),
    //     ) );
});
