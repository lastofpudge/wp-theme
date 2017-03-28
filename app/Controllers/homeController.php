<?php

namespace App\Controllers;

use Timber;

require_once(__DIR__ . '/Traits/GlobalData.php');

class homeController extends Controller
{

    use Traits\GlobalData;

    public function __construct()
    {
        $this->prev_next();
    }

    public static function index()
    {
        /*
         * get timber data
         */
        $returned_data = self::getData();
        $returned_data['foo'] = 'it is data!';

        return $returned_data;
    }

    /*
     * remove prev/next links on homepage
     */
    public static function prev_next()
    {
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    }
}

/*
 * get controller data
 */
$d = new homeController();
