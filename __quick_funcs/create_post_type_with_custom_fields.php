<?php

// create post with data
$new_post = array(
    'post_type'         => 'zayavki',
    'post_status'       => 'pending',
    'post_title'        => wp_strip_all_tags($name)
);

$post_id = wp_insert_post( $new_post );
add_post_meta($post_id, '_user_tel', wp_strip_all_tags($tel));
add_post_meta($post_id, '_user_text', wp_strip_all_tags($comment));

// validate post create
if (is_wp_error($post_id)) {
    wp_send_json([
        'type'    => 'error',
        'message' => 'Ошибка при создании заявки',
    ]);
}
