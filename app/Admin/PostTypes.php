<?php

namespace App\Admin;

use Core\PostType;

class PostTypes
{
    public function __construct()
    {
        // add_action(
        //     "init",
        //     function () {
        //         PostType::register("services", "Services", "Service", true, true, "dashicons-format-aside", ["title", "editor", "thumbnail"]);

        //         PostType::loadTax("services", "services_cat", "Category");
        //         flush_rewrite_rules();
        //     },
        //     0
        // );
    }
}
