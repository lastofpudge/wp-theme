<?php

/*
 * custom route example
 */
Routes::map('custom-url', function ($params) {
    $params = [];
    $params['my_title'] = 'hello this is title from var'; // "This is my custom title"
    Routes::load('routes/custom-templates/custom-url.php', $params, null, 200);
});
