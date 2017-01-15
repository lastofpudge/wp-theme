<?php

/*
 * theme base
 */
namespace Http\adminControllers;

class adminController {
    public static function index(){
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
    }
}

$t = new adminController();
$t::index();