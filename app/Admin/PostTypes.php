<?php

namespace App\Admin;

use PostType;

/**
 * [$url, $name, $singular, $public, $has_archive, $menu_icon, $supports] - paramas
 */

PostType::register('test', 'Test', 'Test', true, true, 'dashicons-format-aside', array( 'title', 'editor'));
