<?php

namespace App\Controllers;

use Timber\Timber;

class Controller
{
    protected array $data;

    public function __construct()
    {
        $this->data = Timber::context();
    }
}
