<?php

/*
 * basic theme options.
 */
$config = [

    'show_posts' => true,

    'show_pages' => true,

    'enable_comments' => true,

    'show_tools' => true,

    'show_admin_bar' => true,

    'create_orders_post_type' => false,

];

/*
 * disablew file edit from admin.
 */
define('DISALLOW_FILE_EDIT', false);

/*
 * alow update without ftp
 */
define('FS_METHOD', 'direct');
