<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/*
 * post/page fields
 */
add_action('carbon_fields_register_fields', function () {

    // Container::make('post_meta', 'Дополнительные поля')
    //     ->show_on_post_type('post')
    //     ->add_fields(array(
    //         Field::make('rich_text', 'new_short_text', 'Описание на главной')
    // ));

   // Container::make('post_meta', 'Home fields')
   // ->where( 'post_type', '=', 'page' )
   // ->where( 'post_template', '=', 'page-home.php' )
   // ->add_tab(__('Text'), array(
   //     Field::make('text', 'th_sub_title', 'Subtitle'),
   //     Field::make('text', 'th_sub_title2', 'Description'),
   //     Field::make('rich_text', 'th_sub_title3', 'Rich text')
   //  ))->add_tab(__('Complex'), array(
   //         Field::make( 'complex', 'tech_items', 'Technologies list' )
           // ->add_fields( array(
           //  Field::make('text', 'tech_title', 'Card title'),
           //  Field::make( 'complex', 'tech_sub_items', 'Technologies subitems' )
            // ->add_fields( array(
            //     Field::make('text', 'tech_title', 'Card title'),
            //     Field::make('image', 'tech_image', '')->set_value_type( 'url' )
            // ))->set_layout( 'tabbed-horizontal' )
        // ))
   // ));

    // Container::make( 'comment_meta', 'Доп. поля' )
    //     ->add_fields( array(
    //         Field::make( 'text', 'comment_rating', 'Рейтинг' ),
    //     ) );

});
