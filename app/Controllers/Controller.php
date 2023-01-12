<?php

namespace App\Controllers;

class Controller
{
    public function __construct()
    {
        add_filter('timber_context', [$this, 'addBreadcrumbs']);
    }

    public function addBreadcrumbs($context)
    {
        if (function_exists('yoast_breadcrumb')) {
            $context['breads'] = yoast_breadcrumb('<div class="breads-wrapper">', '</div>');
        }

        return $context;
    }
}