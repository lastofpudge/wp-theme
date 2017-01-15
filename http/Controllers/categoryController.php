<?php

    namespace Http\Controllers;
    use Timber;

    class categoryController extends baseController
    {
        /*
         * get post data
         */
        public static function index(){
            $data = Timber::get_context();
            $data['pagination'] = Timber::get_pagination();
            $data['posts'] = Timber::get_posts();
            return $data;
        }

    }

    /*
     * get controller data
     */
    $d = new categoryController();