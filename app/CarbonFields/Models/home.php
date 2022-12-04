<?php

//use Carbon_Fields\Container;
//use Carbon_Fields\Field;
//
// Container::make('post_meta', __('Fields for Home', 'crb'))
//    ->where('post_type', '=', 'page')
//    ->where('post_template', '=', 'template-home.php')
//    ->add_fields(array(
//        Field::make('text', 'th_sub_title', 'Subtitle'),
//        Field::make('text', 'th_sub_title2', 'Description'),
//        Field::make('rich_text', 'th_sub_title3', 'Rich text')
//     ))->add_tab(__('Complex'), array(
//            Field::make('complex', 'tech_items', 'Technologies list')
//            ->add_fields(array(
//             Field::make('text', 'tech_title', 'Card title'),
//             Field::make('complex', 'tech_sub_items', 'Technologies sub-items')
//             ->add_fields(array(
//                 Field::make('text', 'tech_title', 'Card title'),
//                 Field::make('image', 'tech_image', '')->set_value_type('url')
//             ))->set_layout('tabbed-horizontal')
//         ))
//    ));
