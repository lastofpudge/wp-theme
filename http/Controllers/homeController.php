<?php

	namespace Http\Controllers;
	use Timber, Redux;

 	class homeController extends baseController
	{
	    public function __construct(){
	        $this->prev_next();
	    }
	    /*
	     * get home data
	     */
	    public static function index(){
            /*
             * get timber data
             */
	        $data = Timber::get_context();
	        $data['foo'] = 'it is data!';

            /*
             * get redux data
             */
            global $redux_opt;
            $data['redux_option_example'] = Redux::getOption($redux_opt, 'text-example');
	        return $data;
	    }

	    /*
	     * remove prev/next links on homepage
	     */
	    public static function prev_next(){
	        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10,0);
	    }

	}

	/*
	 * get controller data
	 */
	$d = new homeController();