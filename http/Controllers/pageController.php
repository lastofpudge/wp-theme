<?php

    namespace Http\Controllers;
    use Timber, TimberPost;

    class pageController extends baseController
    {
        /*
         * get post data
         */
        public static function index(){
            $data = Timber::get_context();
            $post = new TimberPost();
            $data['post'] = $post;
            return $data;
        }

    }

    /*
     * get controller data
     */
    $d = new pageController();