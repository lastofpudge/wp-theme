<?php

/**
 * [$url, $name, $singular, $public, $has_archive, $menu_icon, $supports] - paramas.
 */
PostType::register('test', 'Test', 'Test', true, true, 'dashicons-format-aside', ['title', 'editor']);
