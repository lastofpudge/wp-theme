<?php

add_action('woocommerce_product_after_variable_attributes', 'woo_custom_fields', 10, 3);

function woo_custom_fields($loop, $variation_data, $variation)
{
    woocommerce_wp_text_input(
        array(
            'id' => 'name[' . $loop . ']',
            'label' => 'Name',
            'wrapper_class' => 'form-row',
            'placeholder' => 'Type here...',
            'desc_tip' => 'true',
            'description' => 'We can add some description for a field.',
            'value' => get_post_meta($variation->ID, 'name', true)
        )
    );

    // Textarea
    //    woocommerce_wp_textarea_input(
    //        array(
    //            'id' => 'textarea_field[' . $loop . ']',
    //            'label' => 'Textarea field',
    //            'wrapper_class' => 'form-row',
    //            'value' => get_post_meta($variation->ID, 'textarea_field', true),
    //        )
    //    );
    //
    //    // Select
    //    woocommerce_wp_select(
    //        array(
    //            'id' => 'select_field[' . $loop . ']',
    //            'label' => 'Select field',
    //            'wrapper_class' => 'form-row',
    //            'description' => 'We can add some description for a field.',
    //            'value' => get_post_meta($variation->ID, 'select_field', true),
    //            'options' => array(
    //                'one' => 'Option 1',
    //                'two' => 'Option 2',
    //                'three' => 'Option 3'
    //            )
    //        )
    //    );
    //
    //    woocommerce_wp_radio(
    //        array(
    //            'id' => 'radio_field[' . $loop . ']',
    //            'label' => 'Radio field',
    //            'wrapper_class' => 'form-row',
    //            'value' => get_post_meta($variation->ID, 'radio_field', true),
    //            'options' => array(
    //                'one' => 'Option 1',
    //                'two' => 'Option 2',
    //                'three' => 'Option 3'
    //            )
    //        )
    //    );
    //
    //    woocommerce_wp_checkbox(
    //        array(
    //            'id' => 'my_check[' . $loop . ']',
    //            'label' => 'Checkbox field',
    //            'wrapper_class' => 'form-row',
    //            'value' => get_post_meta($variation->ID, 'my_check', true),
    //        )
    //    );
    //
    //    woocommerce_wp_hidden_input(
    //        array(
    //            'id' => 'hidden_field[' . $loop . ']',
    //            'value' => 'hey there'
    //        )
    //    );
}

add_action('woocommerce_save_product_variation', 'woo_save_fields', 10, 2);

function woo_save_fields($variation_id, $loop): void
{
    // Text Field
    $name = !empty($_POST['name'][$loop]) ? $_POST['name'][$loop] : '';
    update_post_meta($variation_id, 'name', sanitize_text_field($name));

    // Textarea Field
    //    $textarea_field = !empty($_POST['textarea_field'][$loop]) ? $_POST['textarea_field'][$loop] : '';
    //    update_post_meta($variation_id, 'textarea_field', sanitize_textarea_field($textarea_field));
    //
    //    // Select Field
    //    $select_field = !empty($_POST['select_field'][$loop]) ? $_POST['select_field'][$loop] : '';
    //    update_post_meta($variation_id, 'select_field', sanitize_text_field($select_field));
    //
    //
    //    // Radio Field
    //    $radio_field = !empty($_POST['radio_field'][$loop]) ? $_POST['radio_field'][$loop] : '';
    //    update_post_meta($variation_id, 'radio_field', sanitize_text_field($radio_field));
    //
    //    // Checkbox field
    //    $checkbox_field = !empty($_POST['my_check'][$loop]) ? 'yes' : 'no';
    //    update_post_meta($variation_id, 'my_check', $checkbox_field);
    //
    //    // Hidden
    //    update_post_meta($variation_id, '_hidden', $_POST['hidden_field'][$loop]);
}

add_filter('woocommerce_available_variation', function ($variation) {
    $variation['name'] = get_post_meta($variation['variation_id'], 'name', true);
    return $variation;
});
