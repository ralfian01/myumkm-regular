<?php

namespace App\Controllers\Admin\Auth;

use App\Controllers\Admin\AdminController;

class ResetPass extends AdminController
{

    public function __construct()
    {
        // 
    }

    // Server Response
    public function index()
    {

        return $this->login();
    }

    // Function to process admin login
    private function login()
    {

        return $this->tryCatch(
            function ($cbParam) {

                return $this->viewPage('auth/reset_pass');
            }
        );
    }
}
