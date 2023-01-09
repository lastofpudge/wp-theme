<?php

namespace App\Controllers;

use Timber\Term as TimberTerm;
use Timber\Timber;

class CategoryController extends Controller
{
    /**
     * @var array
     */
    private array $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = Timber::get_context();
    }

    public function index(): array
    {
        $this->data['term'] = new TimberTerm();
        $this->data['pagination'] = Timber::get_pagination();
        $this->data['posts'] = Timber::get_posts();

        return $this->data;
    }
}
