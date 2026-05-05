<?php

namespace App\Controllers;

use Timber\Timber;

class AccountController extends Controller
{
    /** @var array */
    private array $data;

    public function __construct()
    {
        parent::__construct();

        $this->data = Timber::context();
    }

    public function checkout(): array
    {
        $this->data['post'] = Timber::get_post();
        return $this->data;
    }

    public function account(): array
    {
        $this->data['post'] = Timber::get_post();
        return $this->data;
    }
}
