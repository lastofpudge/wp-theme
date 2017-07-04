<?php

add_action( 'wp_ajax_contactMail', 'contactMail' );
add_action( 'wp_ajax_nopriv_contactMail', 'contactMail' );

/**
 * contact mail form
 */
function contactMail()
{
    if ( !empty($_POST))
    {
        $nonce = $_POST['nonce'];
        if( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) die( 'Stop!');

        $return = (array(
            'type' => 'success',
            'message' => 'message',
        ));

        wp_send_json($return);
        die();

    }
}
