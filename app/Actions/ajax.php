<?php

header('Content-type: text/html; charset=utf-8');

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

if (!empty($_POST)) {


    $action = $_POST['action'];


    if ($action == "testAction") {
        // check nonce
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            $return = ([
                'type'    => 'error',
                'message' => 'Ошибка nonce',
            ]);
            wp_send_json($return);
            die();
        }
        // load phpmailer
        require_once __DIR__.'/mailer-config.php';

        // load data
        $name = htmlspecialchars(strip_tags($_POST['user_name']));
        $mail = htmlspecialchars(strip_tags($_POST['user_mail']));

        //validate data
        // if (empty($name) || empty($mail)) {
        //     $return = ([
        //         'type'    => 'error',
        //         'message' => 'Вы не заполнили все обязательные поля',
        //     ]);
        //     wp_send_json($return);
        //     die();
        // }

        // // create post with data
        // $new_post = array(
        //     'post_type'         => 'zayavki',
        //     'post_status'       => 'pending',
        //     'post_title'        => wp_strip_all_tags($name)
        // );

        // $post_id = wp_insert_post( $new_post );
        // add_post_meta($post_id, '_user_tel', wp_strip_all_tags($tel));
        // add_post_meta($post_id, '_user_text', wp_strip_all_tags($comment));

        // validate post create
        // if (is_wp_error($post_id)) {
        //      $return = ([
        //         'type'    => 'error',
        //         'message' => 'Ошибка при создании заявки',
        //     ]);
        //     wp_send_json($return);
        //    die();
        // }

        ////send mail
        // header("Access-Control-Allow-Origin: *");

        // $email_to = get_bloginfo('admin_email');
        // $site_name = get_bloginfo( 'name' );

        // $php_mailer->addAddress($email_to, 'SiteTitle');
        // $php_mailer->setFrom = 'SiteTitle';
        // $php_mailer->From = $email_to;
        // $php_mailer->FromName = "SiteTitle Support";
        // $php_mailer->isHTML(true);

        // $php_mailer->Subject = "New order ".$site_name;
        // $php_mailer->Body = '<html><body>';
        // $php_mailer->Body .= "<strong style='display:block; margin-bottom:15px;'> New order: </strong>";
        // $php_mailer->Body .= '<table rules="all" style="border-color: #666; width:100%;border: 1px solid #666;font-size: 12px;" cellpadding="10">';
        // $php_mailer->Body .= "<tr style='background: #eee;'><td><strong>User:</strong> </td><td>".  $name ."</td></tr>";
        // $php_mailer->Body .= "<tr><td><strong>Email:</strong> </td><td>". $mail ."</td></tr>";
        // $php_mailer->Body .= "<tr style='background: #eee;'><td><strong>Phone:</strong> </td><td>".  $phone ."</td></tr>";
        // $php_mailer->Body .= "<tr><td><strong>Message:</strong> </td><td>". $message ."</td></tr>";
        // $php_mailer->Body .= "</table>";
        // $php_mailer->Body .= "</body></html>";

        // if(!$php_mailer->send())
        // {
        //     $return = ([
        //         'type'    => 'fail',
        //         'message' => 'Fail send email',
        //     ]);
        //     wp_send_json($return);
        //     exit;
        // }

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
