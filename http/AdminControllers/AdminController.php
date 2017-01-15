<?php

/*
 * theme base
 */
namespace http\AdminControllers;

class adminController
{
    public static function index(){
//        define( 'DISALLOW_FILE_EDIT', true );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
    }
}

$t = new adminController();
$t::index();