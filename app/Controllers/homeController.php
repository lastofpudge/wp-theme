<?php

namespace App\Controllers;

use Timber;

class homeController extends Controller
{
    public function __construct()
    {
        $this->prev_next();
        $this->returned_data['context'] = Timber::get_context();
    }

    /*
     * get timber data
     */
    public function index()
    {
        // $returned_data['some_post_type'] = Timber::get_posts('post_type=some_post_type&numberposts=-1');
        // dd($this->returned_data);
        return $this->returned_data;
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
