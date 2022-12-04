<?php

if (is_search()) {
    makeView('categoryController@index', 'categories/category');
}

if (is_category()) {
    makeView('categoryController@index', 'categories/category');
}

if (is_single()) {
    makeView('postController@index', 'posts/post');
}

if (is_page()) {
    makeView('pageController@index', 'pages/page');
}

if (is_404()) {
    makeView('errorController@index', 'pages/404');
}
