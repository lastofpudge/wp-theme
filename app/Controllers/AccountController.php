<?php

namespace App\Controllers;

use Timber\Timber;

class AccountController extends Controller
{
    public function account(): array
    {
        $this->data['post'] = Timber::get_post();
        $this->data['is_logged_in'] = is_user_logged_in();
        $this->data['is_wc_endpoint'] = (bool) WC()->query->get_current_endpoint();
        $this->data['registration_enabled'] = 'yes';
        $this->data['lost_password_url'] = wc_get_account_endpoint_url('lost-password');

        return $this->data;
    }
}
