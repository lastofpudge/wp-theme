<?php

/*
 * post/page fields
 */
add_action('carbon_fields_register_fields', function () {
    include __DIR__.'/Fields/home.php';
    include __DIR__.'/Fields/about.php';
    // Container::make( 'comment_meta', 'Comment fields' )
    //     ->add_fields( array(
    //         Field::make( 'text', 'comment_rating', 'Rating' ),
    //     ) );
});
