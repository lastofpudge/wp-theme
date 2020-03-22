<?php

/* is homepage */
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

/* is single page */
if (is_single()) {
    makeView('postController@index', 'posts/post');
}

/* is page */
if (is_page()) {
    makeView('pageController@index', 'pages/page');
}

/* is 404 */
if (is_404()) {
    makeView('errorController@index', 'pages/404');
}
