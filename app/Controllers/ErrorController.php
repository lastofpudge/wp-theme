<?php

namespace App\Controllers;

use Timber\Timber;

class ErrorController extends Controller
{
  /**
   * @var array
   */
  private $data;

  public function __construct()
  {
    parent::__construct();
    $this->data = Timber::get_context();
  }

  public function index(): array
  {
    return $this->data;
  }
}
