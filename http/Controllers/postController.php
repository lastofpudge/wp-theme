<?php

    namespace Http\Controllers;
    use Timber;

    class postController extends baseController
    {
        /*
         * get post data
         */
        public static function index(){
            $context = Timber::get_context();
            $post = Timber::query_post();
            $context['post'] = $post;
            return $context;
        }

    }

    /*
     * get controller data
     */
    $d = new postController();