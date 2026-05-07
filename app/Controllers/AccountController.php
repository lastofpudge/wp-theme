<?php

namespace App\Controllers;

use Timber\Timber;

class AccountController extends Controller
{
    public function account(): array
    {
        $this->data['post'] = Timber::get_post();
        return $this->data;
    }
}
