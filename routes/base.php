<?php

if (is_front_page()) {
    makeView('homeController@index', 'index');
}

if (is_singular('zayavki')) {
    wp_redirect('/', 301);
}

if (is_search()) {
    makeView('categoryController@index', 'categories/category');
}

if (is_category()) {
    makeView('categoryController@index', 'categories/category');
}

/* is single post type-page */
// if (is_singular('test')) :
//     makeView('postController@index', 'posts/post');
// endif;

if (is_single()) {
    makeView('postController@index', 'posts/post');
}

if (is_page()) {
    makeView('pageController@index', 'pages/page');
}

if (is_404()) {
    makeView('errorController@index', 'pages/404');
}
