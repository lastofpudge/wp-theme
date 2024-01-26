<?php

namespace App\Controllers;

class Controller
{
    public function __construct()
    {
        add_filter('timber/context', [$this, 'addBreadcrumbs']);
    }

    public function addBreadcrumbs($context): array
    {
        $context['breads'] = '';
        if (function_exists('yoast_breadcrumb')) {
            $context['breads'] = yoast_breadcrumb('<div class="breads-wrapper">', '</div>');
        }

        return $context;
    }
}
