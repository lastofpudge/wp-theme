<?php

namespace App\Controllers;

use Timber;

class homeController extends Controller
{
    public function __construct()
    {
        $this->prev_next();
    }

    public static function index()
    {
        /*
         * get timber data
         */
        $returned_data = parent::getData();
        // $returned_data['some_post_type'] = Timber::get_posts('post_type=some_post_type&numberposts=-1');
        return $returned_data;
    }

    /*
     * remove prev/next links on homepage
     */
    protected function prev_next()
    {
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    }
}

/*
 * get controller data
 */
$d = new homeController();
