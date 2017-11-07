<?php

add_action('wp_ajax_contactMail', 'contactMail');
add_action('wp_ajax_nopriv_contactMail', 'contactMail');

/**
 * contact mail form.
 */
function contactMail()
{
    if (!empty($_POST)) {
        //validate nonce
        $nonce = $_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'ajax-nonce')) {
            $return = ([
                'type'    => 'error',
                'message' => 'Ошибка nonce',
            ]);
            wp_send_json($return);
            die();
        }

        //data from form
        $name = htmlspecialchars(strip_tags($_POST['user_name']));
        $mail = htmlspecialchars(strip_tags($_POST['user_mail']));

        // example test data
        // $tel = 'tel';
        // $comment = 'comment';

        //validate fields
        if (empty($name) || empty($mail)) {
            $return = ([
                'type'    => 'error',
                'message' => 'Вы не заполнили все обязательные поля',
            ]);
            wp_send_json($return);
            die();
        }

        // create post with data
        // $new_post = array(
        //     'post_type'         => 'zayavki',
        //     'post_status'       => 'pending',
        //     'post_title'        => wp_strip_all_tags($name)
        // );

        // $post_id = wp_insert_post( $new_post );
        // add_post_meta($post_id, '_user_tel', wp_strip_all_tags($tel));
        // add_post_meta($post_id, '_user_text', wp_strip_all_tags($comment));

        //validate post create
        // if (is_wp_error($post_id)) {
        //      $return = ([
        //         'type'    => 'error',
        //         'message' => 'Ошибка при создании заявки',
        //     ]);
        //     wp_send_json($return);
        //    die();
        // }

        //return success
        $return = ([
            'type'    => 'success',
            'message' => 'Ваша заявка была успешно отправлена',
        ]);

        wp_send_json($return);
        die();
    }
}

/*
 * notify about all pending reviews
 */
// add_filter( 'add_menu_classes', 'show_pending_number');
// function show_pending_number( $menu ) {
//     $type = "zayavki";
//     $status = "pending";
//     $num_posts = wp_count_posts( $type, 'readable' );
//     $pending_count = 0;
//     if ( !empty($num_posts->$status) )
//         $pending_count = $num_posts->$status;

//     if ($type == 'post') {
//         $menu_str = 'edit.php';
//     } else {
//         $menu_str = 'edit.php?post_type=' . $type;
//     }

//     foreach( $menu as $menu_key => $menu_data ) {
//         if( $menu_str != $menu_data[2] )
//             continue;
//         $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
//     }
//     return $menu;
// }
