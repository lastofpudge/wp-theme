<?php

	namespace Http\Controllers;
	use Timber;

	class homeController extends baseController
	{
	    public function __construct(){
	        $this->prev_next();
	    }
	    /*
	     * get home data
	     */
	    public static function index(){
	        $data = Timber::get_context();
	        $data['foo'] = 'it is data!';
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