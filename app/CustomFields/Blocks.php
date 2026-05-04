<?php

/*
 * post/page blocks
 */
add_action('carbon_fields_register_fields', function () {
    include __DIR__.'/Blocks/example.php';
});
